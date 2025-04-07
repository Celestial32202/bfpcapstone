const socket = new WebSocket("wss://baranggay-magtanggol.online:8443");


socket.onopen = () => {
    console.log('Connected to the WebSocket server!');
};

socket.onmessage = (event) => {
    console.log('Message from server: ', event.data);
};

socket.onerror = (error) => {
    console.error('WebSocket Error: ', error);
};

socket.onclose = (event) => {
    if (event.wasClean) {
        console.log('Closed cleanly');
    } else {
        console.error('Connection closed unexpectedly');
    }
};
