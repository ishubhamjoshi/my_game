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

    // If it's past 8 PM, set the next draw time to next day 9 AM
    // if ((hours < 9) || (hours > 21 /* || (hours === 9 && minutes >= 0) */)) {
    //     return "Tomorrow 09:00 AM";
    // }

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
        let target = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 9, 0, 0);
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

    // if (hours > 9 || (hours === 9 && minutes >= 0)) { // Starts at 9:00 AM
    //     if (hours <= 21 || (hours === 21 && minutes <= 30)) { // Ends at 9:30 PM
            console.log("Spin started!");
            $("#img1, #img2").addClass("spin-animation");
    
            setTimeout(() => {
                // $("#img1, #img2").removeClass("spin-animation");
                console.log("Spin ended!");
                updateResults();
            }, 5000);
    //     }
    // }
}

function updateResults() {
    $.ajax({
        url: "/get-latest-draw-result", // Adjust the route if needed
        method: "GET",
        success: function(response) {
            $("#finalResult").text(response.final);
            $("#resultAndar").text(response.andar);
            $("#resultBahar").text(response.bahar);

            // Save result in local storage
            localStorage.setItem("lastResult", JSON.stringify(response));

            // Start spin and stop at correct positions
            // spinWheel(response.andar, response.bahar);
        },
        error: function() {
            console.log("No result available");
        }
    });
}

function spinWheel(andarNumber, baharNumber) {
    let anglePerNumber = 36; // 360 degrees / 10 numbers
    let randomSpins = 5 + Math.floor(Math.random() * 5); // 5-10 random full spins

    let andarRotation = randomSpins * 360 + (andarNumber * anglePerNumber); // Adjust for correct stopping position
    let baharRotation = randomSpins * 360 + (baharNumber * anglePerNumber); // Adjust for correct stopping position

    $("#img1").css({
        "transition": "transform 5s ease-out",
        "transform": `rotate(${andarRotation}deg)`
    });

    $("#img2").css({
        "transition": "transform 5s ease-out",
        "transform": `rotate(${baharRotation}deg)`
    });
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
setTimeout(() => {
    startSpin();
    setInterval(startSpin, 15 * 60 * 1000); // Repeat every 15 minutes
}, getNextRunTime());
