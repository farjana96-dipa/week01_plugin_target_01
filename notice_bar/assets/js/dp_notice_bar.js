

document.addEventListener("DOMContentLoaded", function () {
    const noticeBar = document.getElementById("dp-notice-bar");
    const closeBtn = document.getElementById("dp-notice-close");

    if (!noticeBar) return;

    // If NOT closed before → show
    if (localStorage.getItem("dp_notice_bar_closed") !== "yes") {
        noticeBar.style.display = "block";
    }

    // Close button
    if (closeBtn) {
        closeBtn.addEventListener("click", function () {
            noticeBar.style.display = "none";
            localStorage.setItem("dp_notice_bar_closed", "yes");
        });
    }
});
