import {WebSocketServer} from 'ws';

const wss = new WebSocketServer({port: 8080});

// websocket for real time display
wss.on('connection', function connection(ws) {
    console.log("Somebody connected to me.")

    ws.on('message', function message(data, isBinary) {
        console.log(`Received message, broadcasting to ${wss.clients.size - 1} clients.`);
        wss.clients.forEach(function each(client) {
            if (client !== ws && client.readyState === 1) {
                client.send(data, {binary: isBinary});
            }
        });
    });
});

