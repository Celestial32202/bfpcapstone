// import { initializeApp } from "firebase/app";

const firebaseConfig = {
  // apiKey: "AIzaSyAsPdI6hXEW_wLWh8vHzJO1OqbOQxRmZ20",
  // authDomain: "erovoutikacms.firebaseapp.com",
  // databaseURL: "https://erovoutikacms.firebaseio.com",
  // projectId: "erovoutikacms",
  // storageBucket: "erovoutikacms.appspot.com",
  // messagingSenderId: "232975856280",
  // appId: "1:232975856280:web:41ac9c0bb1ce844afb96ed",
  // measurementId: "G-V2M65ZWQPK"


  apiKey: "AIzaSyDjWIssMvyA02E0e2df6JsTdDG6VDEvrNk",
  authDomain: "auscii.firebaseapp.com",
  databaseURL: "https://auscii-default-rtdb.firebaseio.com",
  projectId: "auscii",
  storageBucket: "auscii.appspot.com",
  messagingSenderId: "63244062075",
  appId: "1:63244062075:web:4d8704eef05c5986a941de"

  
  // apiKey: "AIzaSyAThNlMxzsoXAEaKVou5-BY8G62mclK8o8",
  // authDomain: "dive-94ff0.firebaseapp.com",
  // projectId: "dive-94ff0",
  // storageBucket: "dive-94ff0.firebasestorage.app",
  // messagingSenderId: "218315122900",
  // appId: "1:218315122900:web:9427b00189179a04c8b341",
  // measurementId: "G-BCH4V09L84"
};

firebase.initializeApp(firebaseConfig);
// const db = firebase.firestore(), currentUser = firebase.auth().currentUser;

const db = firebase.firestore(), currentUser = firebase.auth().currentUser;
var usersCollection = db.collection('users'), storageReference = firebase.storage().ref();