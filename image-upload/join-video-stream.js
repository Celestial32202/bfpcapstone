const videoContainer = document.getElementById("videoContainer");
const textDiv = document.getElementById("textDiv");
const hlsStatusHeading = document.getElementById("hlsStatusHeading");
const O_TOKEN = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlrZXkiOiIzNzFjOTM2Mi00NjYyLTRkN2ItYjcwNi0wYzAxNmM0ZDFkN2EiLCJwZXJtaXNzaW9ucyI6WyJhbGxvd19qb2luIl0sImlhdCI6MTc0NDIxOTkzNSwiZXhwIjoxNzUxOTk1OTM1fQ.x3lUfTjfiKd7e_4_GNgDmrsFFU6AAokgkrEYBfoMzmg";
const N_TOKEN = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlrZXkiOiJiZDQ2YTMxOC0xNGNmLTRlNWItYTUzMC1hYWI1ZWY0NWNmNTciLCJwZXJtaXNzaW9ucyI6WyJhbGxvd19qb2luIl0sImlhdCI6MTc0NTY3MzM2OSwiZXhwIjoxNzQ4MjY1MzY5fQ.qam25g-gTPTLmaf3g2yDOp6lsvCOXjgrNIbPR9usZ5c";

let meeting = null;
var meetingId = "";
let isMicOn = false;
let isWebCamOn = false;

const Constants = VideoSDK.Constants;

const fetchMeetingRooms = async (_) => {  
  document.getElementById("join-screen").style.display = "none";
  var meetingRooms = await db.collection(meetingRef + meetingsDomain + "/" + roomRef).orderBy("created_at").get();
  console.log("total meetingRooms ->", meetingRooms.docs.length);
  meetingRooms.docs.forEach(v => {
      const d = v.data();
      meetingId = d.meeting_id;
  });
  // meetingId = "u9aa-amv4-fd89";
  console.log("fetchMeetingRooms meetingId ->", meetingId);
  joinMeetingViewer(meetingId);
}

fetchMeetingRooms();

// Initialize meeting
function initializeMeeting(mode) {
    console.log("__initializeMeeting meetingId ->", meetingId);

    window.VideoSDK.config(N_TOKEN);
  
    meeting = window.VideoSDK.initMeeting({
      meetingId: meetingId,
      name: "Admin",
      micEnabled: true,
      webcamEnabled: true
    });
  
    meeting.join();
  
    // creating local participant
    createLocalParticipant();
  
    // setting local participant stream
    meeting.localParticipant.on("stream-enabled", (stream) => {
      setTrack(stream, null, meeting.localParticipant, true);
    });
  
    meeting.on("meeting-joined", () => {
      textDiv.textContent = null;
  
      document.getElementById("grid-screen").style.display = "block";
      // document.getElementById(
      //   "meetingIdHeading"
      // ).textContent = `Meeting Id: ${meetingId}`;
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
  
    // participants left
    meeting.on("participant-left", (participant) => {
      let vElement = document.getElementById(`f-${participant.id}`);
      vElement.remove(vElement);
  
      let aElement = document.getElementById(`a-${participant.id}`);
      aElement.remove(aElement);
    });
    hideLoader();

    /*
    window.VideoSDK.config(N_TOKEN);

    meeting = window.VideoSDK.initMeeting({
        meetingId: meetingId,
        name: "JOHN DOE",
        mode: mode,
    });

    meeting.join();

    meeting.on("meeting-joined", () => {
        textDiv.textContent = null;

        document.getElementById("grid-screen").style.display = "block";
        console.log("__meeting-joined block grid-screen");
        // document.getElementById("meetingIdHeading").textContent = `Meeting Id: ${meetingId}`;

        if (meeting.hlsState === Constants.hlsEvents.HLS_STOPPED) {
            // hlsStatusHeading.textContent = "HLS has not stared yet";
            console.log("HLS has not stared yet");
        } else {
            // hlsStatusHeading.textContent = `HLS Status: ${meeting.hlsState}`;
            console.log("HLS Status: ${meeting.hlsState}");
        }

        if (mode === Constants.modes.CONFERENCE) {
        // we will pin the local participant if he joins in `CONFERENCE` mode
        meeting.localParticipant.pin();

        document.getElementById("speakerView").style.display = "block";
        }
    });

    meeting.on("meeting-left", () => {
        videoContainer.innerHTML = "";
    });

    meeting.on("hls-state-changed", (data) => {
        const { status } = data;
    
        console.log(`HLS Status: ${status}`);
    
        if (mode === Constants.modes.VIEWER) {
          if (status === Constants.hlsEvents.HLS_PLAYABLE) {
            const { downstreamUrl } = data;
            let video = document.createElement("video");
            video.setAttribute("width", "100%");
            video.setAttribute("muted", "false");
            // enableAutoPlay for browser autoplay policy
            video.setAttribute("autoplay", "true");
    
            if (Hls.isSupported()) {
              var hls = new Hls();
              hls.loadSource(downstreamUrl);
              hls.attachMedia(video);
              hls.on(Hls.Events.MANIFEST_PARSED, function () {
                video.play();
              });
            } else if (video.canPlayType("application/vnd.apple.mpegurl")) {
              video.src = downstreamUrl;
              video.addEventListener("canplay", function () {
                video.play();
              });
            }
    
            videoContainer.appendChild(video);
          }
    
          if (status === Constants.hlsEvents.HLS_STOPPING) {
            videoContainer.innerHTML = "";
          }
        }
    });

    if (mode === Constants.modes.CONFERENCE) {
        // creating local participant
        createLocalParticipant();

        // setting local participant stream
        meeting.localParticipant.on("stream-enabled", (stream) => {
        setTrack(stream, null, meeting.localParticipant, true);
        });

        // participant joined
        meeting.on("participant-joined", (participant) => {
        if (participant.mode === Constants.modes.CONFERENCE) {
            participant.pin();

            let videoElement = createVideoElement(
            participant.id,
            participant.displayName
            );

            participant.on("stream-enabled", (stream) => {
            setTrack(stream, audioElement, participant, false);
            });

            let audioElement = createAudioElement(participant.id);
            videoContainer.appendChild(videoElement);
            videoContainer.appendChild(audioElement);
        }
        });

        // participants left
        meeting.on("participant-left", (participant) => {
        let vElement = document.getElementById(`f-${participant.id}`);
        vElement.remove(vElement);

        let aElement = document.getElementById(`a-${participant.id}`);
        aElement.remove(aElement);
        });
    }
    */
}

// creating video element
function createVideoElement(pId, name) {
    let videoFrame = document.createElement("div");
    videoFrame.setAttribute("id", `f-${pId}`);
  
    //create video
    let videoElement = document.createElement("video");
    videoElement.classList.add("video-frame");
    videoElement.setAttribute("id", `v-${pId}`);
    videoElement.setAttribute("playsinline", true);
    // videoElement.setAttribute("width", "300");
  // videoElement.style.textAlign = "center";
    videoElement.setAttribute("width", "400");
    videoElement.setAttribute("height", "500");
    videoFrame.appendChild(videoElement);
  
    let displayName = document.createElement("div");
    displayName.innerHTML = `Name : ${name}`;
  
    videoFrame.appendChild(displayName);
    return videoFrame;
  }
  
  // creating audio element
  function createAudioElement(pId) {
    let audioElement = document.createElement("audio");
    audioElement.setAttribute("autoPlay", "false");
    audioElement.setAttribute("playsInline", "true");
    audioElement.setAttribute("controls", "false");
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

  function joinMeetingViewer(meetingRoomId) {
      textDiv.textContent = "Joining the meeting...";
      showLoader();

      // roomId = document.getElementById("meetingIdTxt").value;
      meetingId = meetingRoomId; //roomId;
      
      initializeMeeting(Constants.modes.VIEWER); //SEND_AND_RECV
      // initializeMeeting(Constants.modes.SIGNALLING_ONLY);
      // initializeMeeting(Constants.modes.VIEWER);
  }

  function showLoader() {
    // document.getElementById("vid-spinner").style.display = "block"; 
  }

  function hideLoader() {
    // document.getElementById("vid-spinner").style.display = "none"; 
  }