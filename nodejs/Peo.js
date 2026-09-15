const axios = require('axios');

const BASE_URL = process.env.BASE_URL || `http://192.168.200.41:8080/analisadata/index.php/`;

const SERVICES = [
    'greeting',
    'biaya',
    'pendaftaran',
    'bpjs',
    'alamat',
    'keluhan',
    'jambesuk'
];

const INTERVAL = 10000; // 10 detik

let running = false;

async function runService() {

    if (running) {
        console.log('Service PEO masih berjalan, skip...');
        return;
    }

    running = true;

    console.log(
        `\n[${new Date().toLocaleString('id-ID')}] Menjalankan service PEO`
    );

    try {

        for (const service of SERVICES) {

            const url =
                `${BASE_URL}/restapi/Whatsapp/PeoMagnolia/${service}`;

            try {

                const response = await axios.get(url, {
                    timeout: 30000
                });

                console.log(
                    `[OK] ${service} -> ${response.status}`
                );

            } catch (error) {

                console.error(
                    `[ERROR] ${service} ->`,
                    error.response?.status || error.message
                );

            }
        }

    } finally {

        running = false;

    }
}


// Jalankan pertama kali
runService();


// Jalankan setiap 10 detik
setInterval(runService, INTERVAL);