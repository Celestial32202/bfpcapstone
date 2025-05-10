const leaveButton = document.getElementById("leaveBtn");
const toggleMicButton = document.getElementById("toggleMicBtn");
const toggleWebCamButton = document.getElementById("toggleWebCamBtn");
const createButton = document.getElementById("createMeetingBtn");
const videoContainer = document.getElementById("videoContainer");
const textDiv = document.getElementById("textDiv");
const hlsStatusHeading = document.getElementById("hlsStatusHeading");
const O_TOKEN = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlrZXkiOiIzNzFjOTM2Mi00NjYyLTRkN2ItYjcwNi0wYzAxNmM0ZDFkN2EiLCJwZXJtaXNzaW9ucyI6WyJhbGxvd19qb2luIl0sImlhdCI6MTc0NDIxOTkzNSwiZXhwIjoxNzUxOTk1OTM1fQ.x3lUfTjfiKd7e_4_GNgDmrsFFU6AAokgkrEYBfoMzmg";
const N_TOKEN = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlrZXkiOiJiZDQ2YTMxOC0xNGNmLTRlNWItYTUzMC1hYWI1ZWY0NWNmNTciLCJwZXJtaXNzaW9ucyI6WyJhbGxvd19qb2luIl0sImlhdCI6MTc0NTY3MzM2OSwiZXhwIjoxNzQ4MjY1MzY5fQ.qam25g-gTPTLmaf3g2yDOp6lsvCOXjgrNIbPR9usZ5c";

let meeting = null;
let meetingId = "";
let isMicOn = false;
let isWebCamOn = false;

const Constants = VideoSDK.Constants;

const initMeetingAndJoinVideoConference = async (_) => {  
  textDiv.textContent = "Please wait. Video Stream Loading...";
  showLoader();
  document.getElementById("createMeetingBtn").style.display = "none";

  const url = `https://api.videosdk.live/v2/rooms`;
  const options = {
    method: "POST",
    headers: { Authorization: N_TOKEN, "Content-Type": "application/json" },
  };
  const { roomId } = await fetch(url, options)
    .then((response) => response.json())
    .catch((error) => alert("error", error));

  meetingId = roomId;

  initializeMeeting(Constants.modes.CONFERENCE, "John Doe Delacruz");
}

initMeetingAndJoinVideoConference();

// createButton.addEventListener("click", async () => {
//   document.getElementById("join-screen").style.display = "none";
//   textDiv.textContent = "Please wait, we are joining the meeting";

//   const url = `https://api.videosdk.live/v2/rooms`;
//   const options = {
//     method: "POST",
//     headers: { Authorization: N_TOKEN, "Content-Type": "application/json" },
//   };
//   const { roomId } = await fetch(url, options)
//     .then((response) => response.json())
//     .catch((error) => alert("error", error));

//   meetingId = roomId;

//   initializeMeeting(Constants.modes.CONFERENCE, "John Doe Delacruz");
// });

function uuidv4() {
  return "10000000-1000-4000-8000-100000000000".replace(/[018]/g, c => (+c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> +c / 4).toString(16));
}

function initializeMeeting(mode, residentName) {
  console.log("___initializeMeeting...");
  window.VideoSDK.config(N_TOKEN);
  // showLoader();

  meeting = window.VideoSDK.initMeeting({
    meetingId: meetingId,
    name: "Resident User's Video",
    micEnabled: true,
    webcamEnabled: true
  });

  meeting.join();

  createLocalParticipant();

  meeting.localParticipant.on("stream-enabled", (stream) => {
    setTrack(stream, null, meeting.localParticipant, true);
  });

  meeting.on("meeting-joined", () => {
    textDiv.textContent = null;

    document.getElementById("grid-screen").style.display = "block";
    // document.getElementById("meetingIdHeading").textContent = `Meeting Id: ${meetingId}`;
    // console.log(`___Meeting Id: ${meetingId}`);

    var uuid = uuidv4();
    
    db.collection(meetingRef).doc(meetingsDomain).collection(roomRef).add({
      id: uuid,
      meeting_id: meetingId,
      platform: web,
      dependency: videoSDK,
      is_active: true,
      created_at: serverDateTime
    })
    .then(function(_) {})
    .catch(function (e) {
        console.log("db.collection error ->", e.message);
    });
    console.log("Successfully insert the meeting id ->"+ meetingId + " via Firestore Database");
    hideLoader();
  });

  meeting.on("meeting-left", () => {
    videoContainer.innerHTML = "";
  });

  //  participant joined
  meeting.on("participant-joined", (participant) => {
    let videoElement = createVideoElement(
      participant.id,
      participant.displayName
    );
    let audioElement = createAudioElement(participant.id);

    participant.on("stream-enabled", (stream) => {
      setTrack(stream, audioElement, participant, false);
    });
    videoContainer.appendChild(videoElement);
    videoContainer.appendChild(audioElement);
  });

  meeting.on("participant-left", (participant) => {
    let vElement = document.getElementById(`f-${participant.id}`);
    vElement.remove(vElement);

    let aElement = document.getElementById(`a-${participant.id}`);
    aElement.remove(aElement);
  });
  console.log("___END initializeMeeting...");
}
  
function createVideoElement(pId, name) {
  let videoFrame = document.createElement("div");
  videoFrame.setAttribute("id", `f-${pId}`);

  //create video
  let videoElement = document.createElement("video");
  videoElement.classList.add("video-frame");
  videoElement.setAttribute("id", `v-${pId}`);
  videoElement.setAttribute("playsinline", true);
  // videoElement.style.textAlign = "center";
  videoElement.setAttribute("width", "400");
  videoElement.setAttribute("height", "500");
  videoFrame.appendChild(videoElement);

  let displayName = document.createElement("div");
  displayName.innerHTML = `${name}`;

  videoFrame.style.cssText = "text-align: center; text-decoration: underline; font-size: 120%;";
  videoFrame.appendChild(displayName);
  return videoFrame;
  }
  
  // creating audio element
  function createAudioElement(pId) {
    let audioElement = document.createElement("audio");
    audioElement.setAttribute("autoPlay", "true");
    audioElement.setAttribute("playsInline", "true");
    audioElement.setAttribute("controls", "true");
    audioElement.setAttribute("id", `a-${pId}`);
    audioElement.style.display = "none";
    return audioElement;
  }
  
  // creating local participant
  function createLocalParticipant() {
    let localParticipant = createVideoElement(
      meeting.localParticipant.id,
      meeting.localParticipant.displayName
    );
    videoContainer.appendChild(localParticipant);
  }
  
  // setting media track
  function setTrack(stream, audioElement, participant, isLocal) {
    if (stream.kind == "video") {
      isWebCamOn = true;
      const mediaStream = new MediaStream();
      mediaStream.addTrack(stream.track);
      let videoElm = document.getElementById(`v-${participant.id}`);
      videoElm.srcObject = mediaStream;
      videoElm
        .play()
        .catch((error) =>
          console.error("videoElem.current.play() failed", error)
        );
    }
    if (stream.kind == "audio") {
      if (isLocal) {
        isMicOn = true;
      } else {
        const mediaStream = new MediaStream();
        mediaStream.addTrack(stream.track);
        audioElement.srcObject = mediaStream;
        audioElement
          .play()
          .catch((error) => console.error("audioElem.play() failed", error));
      }
    }
  }

  // leave Meeting Button Event Listener
  leaveButton.addEventListener("click", async () => {
    meeting?.leave();
    document.getElementById("grid-screen").style.display = "none";
    document.getElementById("join-screen").style.display = "block";
  });
  
  // Toggle Mic Button Event Listener
  toggleMicButton.addEventListener("click", async () => {
    if (isMicOn) {
      // Disable Mic in Meeting
      meeting?.muteMic();
    } else {
      // Enable Mic in Meeting
      meeting?.unmuteMic();
    }
    isMicOn = !isMicOn;
  });
  
  // Toggle Web Cam Button Event Listener
  toggleWebCamButton.addEventListener("click", async () => {
    if (isWebCamOn) {
      // Disable Webcam in Meeting
      meeting?.disableWebcam();
  
      let vElement = document.getElementById(`f-${meeting.localParticipant.id}`);
      vElement.style.display = "none";
    } else {
      // Enable Webcam in Meeting
      meeting?.enableWebcam();
  
      let vElement = document.getElementById(`f-${meeting.localParticipant.id}`);
      vElement.style.display = "inline";
    }
    isWebCamOn = !isWebCamOn;
  });

  function showLoader() {
    // document.getElementById("vid-spinner").style.display = "block"; 
  }

  function hideLoader() {
    // document.getElementById("vid-spinner").style.display = "none"; 
  }