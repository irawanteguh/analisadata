<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Routingsystem{
        protected static $app;

        public static $segment1   = '';
        public static $segment2   = '';
        public static $pageTitle  = 'Analisa Data';
        public static $resultmenu = [];
        public static $activeMenu = [];

        private static array $children = [];
        private static array $activeCache = [];
        private static array $publicRoutes = [
            '',
            'auth',
            'booking',
            'public',
            'api',
            'cron',
            'callback',
            'webhook'
        ];

        public static function system(): void
        {
            self::$app = &get_instance();

            self::init();
            self::checkSession();
            self::category();
        }

        private static function init(): void
        {
            self::$children    = [];
            self::$activeCache = [];
            self::$activeMenu  = [];
            self::$pageTitle   = 'Analisa Data';

            self::$app->load->model('Modelrouting');

            self::$segment1   = (string) self::$app->uri->segment(1);
            self::$segment2   = (string) self::$app->uri->segment(2);
            self::$resultmenu = self::$app->Modelrouting->menu($_SESSION['userid']) ?: [];

            self::buildIndex();
        }

        private static function checkSession(): void
        {
            if (!self::$app) {
                self::$app = &get_instance();
            }

            $segment = self::$segment1 !== '' ? self::$segment1 : (string) self::$app->uri->segment(1);
            $route = trim($segment.'/'.self::$segment2, '/');

            if (in_array($segment, self::$publicRoutes, true)) {
                return;
            }

            if (self::isLoggedIn()) {
                return;
            }

            self::$app->session->set_flashdata('redirect_url', current_url());
            redirect('auth/sign');
            exit;
        }

        private static function isLoggedIn(): bool
        {
            return (bool) self::$app->session->userdata('loggedin');
        }

        private static function buildIndex(): void
        {
            foreach (self::$resultmenu as $menu) {
                $parentId = trim((string) ($menu['MODULES_HEADER_ID'] ?? ''));

                if ($parentId !== '') {
                    self::$children[$parentId][] = $menu;
                }

                if (self::menuActive($menu)) {
                    self::$activeMenu = $menu;
                    self::$pageTitle = (string) ($menu['MODULES_NAME'] ?? 'Analisa Data');
                }
            }
        }

        private static function category(): void
        {
            self::$app->load->vars([
                'menu'             => self::buildSidebar(),
                'menunavbar'       => self::buildNavbar(),
                'pageTitle'        => self::e(self::$pageTitle),
                'activeMenu'       => self::$activeMenu
            ]);
        }

        private static function buildSidebar(): string
        {
            $html = '';

            foreach (self::$resultmenu as $menu) {
                if (!self::isRootHeader($menu)) {
                    continue;
                }

                $html .= '
                    <div class="menu-item">
                        <div class="menu-content pb-2">
                            <span class="menu-section text-muted text-uppercase fs-8 ls-1 fw-bolder">
                                '.self::e($menu['MODULES_NAME'] ?? '').'
                            </span>
                        </div>
                    </div>';

                $id = self::menuId($menu);
                $html .= self::generateSidebar($id, [$id => true]);
            }

            foreach (self::$resultmenu as $menu) {
                if (!self::isTopLevelMenu($menu)) {
                    continue;
                }

                $html .= self::generateSidebarItem($menu);
            }

            return $html;
        }

        private static function generateOrganization(array $menu): string
        {
            $id = self::menuId($menu);

            if ($id === '') {
                return '';
            }

            $children = self::$children[$id] ?? [];

            // Jika masih mempunyai child, tampilkan seluruh child secara rekursif
            if (!empty($children)) {

                $html = '';

                foreach ($children as $child) {
                    $html .= self::generateOrganization($child);
                }

                return $html;
            }

            $active = self::menuActive($menu) ? 'active' : '';

            return '
                <li class="nav-item">
                    <a class="nav-link text-active-primary me-6 '.$active.'"
                        href="'.self::e(self::menuUrl($menu)).'">
                        '.self::e($menu['MODULES_NAME']).'
                    </a>
                </li>';
        }

        private static function buildNavbar(): string
        {
            $html = '';

            foreach (self::$resultmenu as $menu) {
                if (!self::isRootHeader($menu)) {
                    continue;
                }

                $html .= self::generateNavbar($menu);
            }

            foreach (self::$resultmenu as $menu) {
                if (!self::isTopLevelMenu($menu)) {
                    continue;
                }

                $html .= self::generateNavbar($menu);
            }

            return $html;
        }

        private static function generateSidebar(string $parentId, array $visited = []): string
        {
            $html = '';

            foreach (self::$children[$parentId] ?? [] as $menu) {
                $id = self::menuId($menu);

                if ($id === '' || isset($visited[$id])) {
                    continue;
                }

                $hasChild = !empty(self::$children[$id]);
                $isOpen = self::isMenuActive($id);
                $isActive = self::menuActive($menu);
                $nextVisited = $visited + [$id => true];

                if ($hasChild) {
                    $html .= '
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion '.($isOpen ? 'show' : '').'">
                            <span class="menu-link '.($isOpen ? 'active' : '').'">
                                '.self::menuLeading($menu).'
                                <span class="menu-title">
                                    '.self::e($menu['MODULES_NAME'] ?? '').'
                                </span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                '.self::generateSidebar($id, $nextVisited).'
                            </div>
                        </div>';

                    continue;
                }

                $html .= '
                    <div class="menu-item">
                        <a class="menu-link '.($isActive ? 'active' : '').'" href="'.self::e(self::menuUrl($menu)).'">
                            '.self::menuLeading($menu).'
                            <span class="menu-title">
                                '.self::e($menu['MODULES_NAME'] ?? '').'
                            </span>
                        </a>
                    </div>';
            }

            return $html;
        }

        private static function generateSidebarItem(array $menu, array $visited = []): string
        {
            $id = self::menuId($menu);

            if ($id === '' || isset($visited[$id])) {
                return '';
            }

            $hasChild = !empty(self::$children[$id]);
            $isOpen = self::isMenuActive($id);
            $isActive = self::menuActive($menu);
            $nextVisited = $visited + [$id => true];

            if ($hasChild) {
                return '
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion '.($isOpen ? 'show' : '').'">
                        <span class="menu-link '.($isOpen ? 'active' : '').'">
                            '.self::menuLeading($menu).'
                            <span class="menu-title">
                                '.self::e($menu['MODULES_NAME'] ?? '').'
                            </span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            '.self::generateSidebar($id, $nextVisited).'
                        </div>
                    </div>';
            }

            return '
                <div class="menu-item">
                    <a class="menu-link '.($isActive ? 'active' : '').'" href="'.self::e(self::menuUrl($menu)).'">
                        '.self::menuLeading($menu).'
                        <span class="menu-title">
                            '.self::e($menu['MODULES_NAME'] ?? '').'
                        </span>
                    </a>
                </div>';
        }

        private static function generateNavbar(array $menu): string
        {
            $id       = self::menuId($menu);
            $children = self::$children[$id] ?? [];
            $isActive = self::menuActive($menu);
            $isOpen   = $isActive || self::isMenuActive($id);

            if (empty($children)) {
                return '
                    <div class="menu-item me-lg-1">
                        <a href="'.self::e(self::menuUrl($menu)).'" class="menu-link py-3 '.($isActive ? 'active' : '').'">
                            <span class="menu-title">
                                '.self::e($menu['MODULES_NAME'] ?? '').'
                            </span>
                        </a>
                    </div>';
            }

            $html = '
                <div data-kt-menu-trigger="{default:\'click\', lg:\'hover\'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion me-lg-1">
                    <span class="menu-link py-3 '.($isOpen ? 'active' : '').'">
                        <span class="menu-title">
                            '.self::e($menu['MODULES_NAME'] ?? '').'
                        </span>
                        <span class="menu-arrow d-lg-none"></span>
                    </span>
                    <div class="menu-sub menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px">';

            foreach ($children as $child) {
                $html .= self::generateNavbarChild($child, [$id => true]);
            }

            return $html.'
                    </div>
                </div>';
        }

        private static function generateNavbarChild(array $menu, array $visited = []): string
        {
            $id = self::menuId($menu);

            if ($id === '' || isset($visited[$id])) {
                return '';
            }

            $children = self::$children[$id] ?? [];
            $isActive = self::menuActive($menu);
            $isOpen = $isActive || self::isMenuActive($id);
            $nextVisited = $visited + [$id => true];

            if (empty($children)) {
                return '
                    <div class="menu-item">
                        <a href="'.self::e(self::menuUrl($menu)).'" class="menu-link py-3 '.($isActive ? 'active' : '').'">
                            '.self::menuLeading($menu).'
                            <span class="menu-title">
                                '.self::e($menu['MODULES_NAME'] ?? '').'
                            </span>
                        </a>
                    </div>';
            }

            $html = '
                <div data-kt-menu-trigger="{default:\'click\', lg:\'hover\'}" data-kt-menu-placement="right-start" class="menu-item menu-lg-down-accordion">
                    <span class="menu-link py-3 '.($isOpen ? 'active' : '').'">
                        '.self::menuLeading($menu).'
                        <span class="menu-title">
                            '.self::e($menu['MODULES_NAME'] ?? '').'
                        </span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px">';

            foreach ($children as $child) {
                $html .= self::generateNavbarChild($child, $nextVisited);
            }

            return $html.'
                    </div>
                </div>';
        }

        private static function isMenuActive(string $id, array $visited = []): bool
        {
            if (isset(self::$activeCache[$id])) {
                return self::$activeCache[$id];
            }

            if ($id === '' || isset($visited[$id])) {
                return false;
            }

            $visited[$id] = true;

            foreach (self::$children[$id] ?? [] as $child) {
                if (self::menuActive($child) || self::isMenuActive(self::menuId($child), $visited)) {
                    self::$activeCache[$id] = true;
                    return true;
                }
            }

            self::$activeCache[$id] = false;
            return false;
        }

        private static function menuActive(array $menu): bool
        {
            return (string) ($menu['PACKAGE'] ?? '') === self::$segment1
                && (string) ($menu['DEF_CONTROLLER'] ?? '') === self::$segment2;
        }

        private static function menuUrl(array $menu): string
        {
            $package = (string) ($menu['PACKAGE'] ?? '');
            $controller = (string) ($menu['DEF_CONTROLLER'] ?? '');

            if ($package === '' || $controller === '') {
                return '#';
            }

            return site_url($package.'/'.$controller);
        }

        private static function menuId(array $menu): string
        {
            return trim((string) ($menu['MODULES_ID'] ?? ''));
        }

        private static function menuLeading(array $menu): string
        {
            $icon = trim((string) ($menu['ICON'] ?? ''));

            if ($icon !== '') {
                return '
                    <span class="menu-icon">
                        <i class="'.self::e($icon).' fs-2"></i>
                    </span>';
            }

            return '
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>';
        }

        private static function isRootHeader(array $menu): bool
        {
            return empty($menu['MODULES_HEADER_ID']) && (string) ($menu['PARENT'] ?? '') === 'C';
        }

        private static function isTopLevelMenu(array $menu): bool
        {
            return empty($menu['MODULES_HEADER_ID']) && (string) ($menu['PARENT'] ?? '') !== 'C';
        }

        private static function e($value): string
        {
            return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        }
    }
?>