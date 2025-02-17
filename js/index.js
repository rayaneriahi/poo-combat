const audio = new Audio();
audio.src = "song/menu.mp3";

document.addEventListener("click", () => {
    audio.play();
});

audio.addEventListener("ended", () => {
    audio.currentTime = 0;
    audio.play();
});