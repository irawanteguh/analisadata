import os from "os";
import fetch from "node-fetch";
import chalk from "chalk";

let lebar             = 135;
let host              = "localhost";
let isRunningServices = false;


const interfaces = os.networkInterfaces();
for (const iface of Object.values(interfaces)) {
    for (const info of iface) {
        if (info.family === "IPv4" && !info.internal) {
            host = info.address;
            break;
        }
    }
}

const BASE_URL = process.env.BASE_URL || `http://${host}/analisadata/index.php/`;

function getTimeStamp() {
  const now = new Date();
  const pad = (n) => n.toString().padStart(2, "0");
  return `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
}

function printHeader() {
    console.log(chalk.cyan("=".repeat(lebar)));
    console.log(chalk.cyan("TIMESTAMP".padEnd(40)+"METHOD".padEnd(10)+"ENDPOINT".padEnd(30)+"STATUS".padEnd(12)+"MESSAGE"));
    console.log(chalk.cyan("=".repeat(lebar)));
}

function formatLog(
    timestamp,
    method,
    endpoint,
    status,
    message,
    widths = { ts: 40, method: 10, endpoint: 30, status: 12 },
    colors = { ts: "white", method: "yellow", endpoint: "cyan", status: "auto", message: "white" }
) {
    // Fungsi bantu untuk dapatkan warna dari chalk secara dinamis
    const colorize = (text, color) => {
        if (color === "auto") {
            // Otomatis hijau untuk 2xx, merah untuk lainnya
            const statusNum = Number(status);
            const statusColor = (statusNum >= 200 && statusNum < 300) ? "green" : "red";
            return chalk[statusColor](text);
        }
        return chalk[color] ? chalk[color](text) : text;
    };

    return (
        colorize(String(timestamp).padEnd(widths.ts), colors.ts) +
        colorize(String(method).padEnd(widths.method), colors.method) +
        colorize(String(endpoint).padEnd(widths.endpoint), colors.endpoint) +
        colorize(String(status).padEnd(widths.status), colors.status) +
        colorize(String(message || ""), colors.message)
    );
}

async function callAPI(endpoint, method = "GET", body = null) {
    printHeader();
    const url     = `${BASE_URL}${endpoint}`;
    const options = {method,headers: { "Content-Type": "application/json" }};

    if (body) options.body = JSON.stringify(body);

    try{
        const response = await fetch(url, options);
        const text = await response.text();


        // if (!response.ok) {
        //     const match = text.match(/<strong>Message:<\/strong>\s*([^<]+)/i);
        //     const errorMsg = match ? match[1].trim() : response.statusText;

        //     console.log(
        //         formatLog(
        //             getTimeStamp(),
        //             method,
        //             endpoint,
        //             response.status,
        //             response.statusText,
        //             { ts: 40, method: 10, endpoint: 30, status: 12 }, // custom width
        //             { ts: "white", method: "yellow", endpoint: "yellow", status: "auto", message: "auto" } // custom warna
        //         )
        //     );

        //     console.log(chalk.red("*".repeat(lebar)));
        //     console.log(chalk.red(errorMsg));
        //     console.log(chalk.red("*".repeat(lebar)));
            
        //     return;
        // }

        if (!response.ok) {

            console.log(
                formatLog(
                    getTimeStamp(),
                    method,
                    endpoint,
                    response.status,
                    response.statusText
                )
            );

            console.log(chalk.red("*".repeat(lebar)));
            console.log(chalk.red(text)); // tampilkan full response
            console.log(chalk.red("*".repeat(lebar)));

            return;
        }

        try{
            const data = JSON.parse(text);

            console.log(
                formatLog(
                    getTimeStamp(),
                    method,
                    endpoint,
                    response.status,
                    response.statusText,
                    { ts: 40, method: 10, endpoint: 30, status: 12 }, // custom width
                    { ts: "white", method: "yellow", endpoint: "yellow", status: "auto", message: "auto" } // custom warna
                )
            );

            console.log(data);
        }catch{
            const match   = text.match(/<strong>Message:<\/strong>\s*([^<]+)/i);
            const message = match ? match[1].trim() : text;

            console.log(
                formatLog(
                    getTimeStamp(),
                    method,
                    endpoint,
                    response.status,
                    response.statusText,
                    { ts: 40, method: 10, endpoint: 30, status: 12 }, // custom width
                    { ts: "white", method: "yellow", endpoint: "yellow", status: "auto", message: "auto" } // custom warna
                )
            );

            console.log(chalk.red(message));
        }

    }catch(error){
        console.log(
            formatLog(
                getTimeStamp(),
                method,
                endpoint,
                "Network Error",
                error.message,
                { ts: 40, method: 10, endpoint: 30, status: 12 }, // custom width
                { ts: "white", method: "yellow", endpoint: "yellow", status: "auto", message: "auto" } // custom warna
            )
        );
    }
}

async function runservices() {
    await callAPI("rujukanbpjs", "GET");
}

async function runservicesSafe() {
    if (isRunningServices) {
        return;
    }

    isRunningServices = true;

    try {
        await runservices();
    } catch (e) {
        console.error("❌ Error runservices:", e.message);
    } finally {
        isRunningServices = false;
    }
}

console.clear();
runservicesSafe();
setInterval(runservicesSafe, 10000);