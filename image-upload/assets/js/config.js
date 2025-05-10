const firebaseConfig = {
    apiKey: "AIzaSyAThNlMxzsoXAEaKVou5-BY8G62mclK8o8",
    authDomain: "dive-94ff0.firebaseapp.com",
    projectId: "dive-94ff0",
    storageBucket: "dive-94ff0.firebasestorage.app",
    messagingSenderId: "218315122900",
    appId: "1:218315122900:web:9427b00189179a04c8b341",
    measurementId: "G-BCH4V09L84"
  
    // apiKey: "AIzaSyAgJv3t_i3v7h5uQsAeQ2092GI21egb4g8",
    // authDomain: "baranggay-magtanggol-online.firebaseapp.com",
    // projectId: "baranggay-magtanggol-online",
    // storageBucket: "baranggay-magtanggol-online.firebasestorage.app",
    // messagingSenderId: "177344231241",
    // appId: "1:177344231241:web:db83d7fcb9c1587703cc98"
  };
  
  firebase.initializeApp(firebaseConfig);
  
  const db = firebase.firestore(), currentUser = firebase.auth().currentUser;