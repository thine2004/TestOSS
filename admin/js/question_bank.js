// Timer Logic
// durationMinutes must be defined in the main PHP file before loading this script

let time = durationMinutes * 60;
const timerElement = document.getElementById('timer');

const interval = setInterval(() => {
    let hours = Math.floor(time / 3600);
    let minutes = Math.floor((time % 3600) / 60);
    let seconds = time % 60;

    hours = hours < 10 ? '0' + hours : hours;
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;

    if (timerElement) {
        timerElement.innerHTML = `${hours}:${minutes}:${seconds}`;
    }

    if (time > 0) {
        time--;
    } else {
        clearInterval(interval);
        alert("Đã hết thời gian làm bài! Hệ thống sẽ tự động nộp bài.");
        const form = document.getElementById('examForm');
        if (form) form.submit();
    }
}, 1000);

// Prevent accidental leave
window.onbeforeunload = function() {
    // return "Bạn có chắc muốn rời khỏi bài thi?";
};

const examForm = document.getElementById('examForm');
if (examForm) {
    examForm.addEventListener('submit', function() {
        window.onbeforeunload = null; // Disable warning on valid submit
    });
}
