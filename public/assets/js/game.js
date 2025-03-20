function updateClock() {
    let now = new Date();
    let h = now.getHours();
    let m = now.getMinutes();
    let s = now.getSeconds();
    document.getElementById("digital-clock").innerHTML =
        `${h < 10 ? "0" : ""}${h}:${m < 10 ? "0" : ""}${m}:${s < 10 ? "0" : ""}${s}`;
}

function nextDrawTime() {
    let now = new Date();
    let hours = now.getHours();
    let minutes = now.getMinutes();

    if ((hours < 9) || (hours > 21 || (hours === 21 && minutes >= 30))) {
        return "Tomorrow 09:00 AM";
    }

    // Calculate the next draw time in 15-minute intervals
    let nextMinutes = Math.ceil(minutes / 15) * 15;
    let nextHour = hours;

    if (nextMinutes === 60) {
        nextMinutes = 0;
        nextHour += 1;
    }

    return `${nextHour < 10 ? "0" : ""}${nextHour}:${nextMinutes < 10 ? "0" : ""}${nextMinutes}`;
}

function updateCountdown() {
    let now = new Date();
    let nextTime = nextDrawTime(); // Get the next draw time

    // Update the "Next Draw Time" display dynamically
    $("#nextDrawTime").text(nextTime);

    if (nextTime === "Tomorrow 09:00 AM") {
        let now = new Date();
        let target;
        if (now.getHours() < 9) {
            target = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 9, 0, 0);
        } else {
            target = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 9, 0, 0);
        }
        let diff = target - now;

        if (diff <= 0) {
            document.getElementById("remainingTime").innerHTML = "00:00:00";
            return;
        }

        let hours = Math.floor(diff / (1000 * 60 * 60));
        let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((diff % (1000 * 60)) / 1000) + 1;

        document.getElementById("remainingTime").innerHTML =
            `${hours < 10 ? "0" : ""}${hours}:${minutes < 10 ? "0" : ""}${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
    } else {
        let nextHour = parseInt(nextTime.split(":")[0]);
        let nextMinutes = parseInt(nextTime.split(":")[1]);
        let target = new Date(now.getFullYear(), now.getMonth(), now.getDate(), nextHour, nextMinutes, 0);
        let diff = target - now;

        if (diff <= 0) {
            document.getElementById("remainingTime").innerHTML = "00:00";
            return;
        }

        let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = (Math.floor((diff % (1000 * 60)) / 1000)) + 1;

        document.getElementById("remainingTime").innerHTML =
            `${minutes < 10 ? "0" : ""}${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
    }
}

$(document).ready(function () {
    setInterval(updateClock, 1000);
    setInterval(updateCountdown, 1000); // Ensure next draw time updates
    showTodayDate();
    startSpin();
});


function showTodayDate() {
    let now = new Date();
    let day = now.getDate();
    let month = now.getMonth() + 1; // Months are zero-based
    let year = now.getFullYear();
    document.getElementById("todayDate").innerHTML = `${day < 10 ? "0" : ""}${day}-${month < 10 ? "0" : ""}${month}-${year}`;
}

function startSpin() {
    let now = new Date();
    let hours = now.getHours();
    let minutes = now.getMinutes();

    if (hours > 9 || (hours === 9 && minutes >= 0)) { // Starts at 9:00 AM
        if (hours <= 21 || (hours === 21 && minutes <= 30)) { // Ends at 9:30 PM
            console.log("Spin started!");
            $("#img1, #img2").addClass("spin-animation");
            setTimeout(() => {
                console.log("Spin ended!");
                updateResults();
            }, 500);
        }
    }
}

function updateResults() {
    $.ajax({
        url: "/get-latest-draw-result", // Adjust the route if needed
        method: "GET",
        success: function (response) {
            spinWheel(response.andar, response.bahar);
        },
        error: function () {
            console.log("No result available");
        }
    });
}

function spinWheel(resultAndar, resultBahar) {
    // Set both resultAndar and resultBahar spins using a common function
    var spin1 = getSpinValue(resultAndar);
    var spin2 = getSpinValue(resultBahar);

    rotateWheel('#img1', spin1, resultAndar, resultBahar);
    rotateWheel('#img2', spin2, resultAndar, resultBahar);
}

// Get the spin value based on the result
function getSpinValue(result) {
    const spinValues = {
        1: 114,
        2: 77,
        3: 41,
        4: 4,
        5: 329,
        6: 293,
        7: 258,
        8: 221,
        9: 186,
        0: 150
    };
    return spinValues[result] || 150;
}

// General function to rotate the wheel
function rotateWheel(selector, resultn, resultAndar, resultBahar) {
    const deg = parseFloat(resultn) + 36000;
    $(selector).css({
        'transition': 'transform 8s cubic-bezier(0.25, 0.8, 0.25, 1)',
        'transform': `rotate(${deg}deg)`
    });
    $(selector)[0].addEventListener('transitionend', function () {
        $("#img1, #img2").removeClass("spin-animation");
        $("#finalResult").text(resultAndar.toString() + resultBahar.toString());
        $("#resultAndar").text(resultAndar);
        $("#resultBahar").text(resultBahar);
    }, { once: true });
}

function getNextRunTime() {
    let now = new Date();
    let minutes = now.getMinutes();
    let seconds = now.getSeconds();

    // Calculate the minutes remaining to the next 15-minute mark
    let remainingMinutes = 15 - (minutes % 15);
    let remainingTime = (remainingMinutes * 60 - seconds) * 1000; // Convert to milliseconds

    return remainingTime;
}

// Align first execution to the next 15-minute mark
setTimeout(function () {
    window.location.reload();
}, getNextRunTime());
