document.addEventListener("change", e => {
    if (e.target.matches("select[name='slot'], select[name='doctor'], input[name='date']")) {
        let doctor = document.querySelector("select[name='doctor']").value;
        let date = document.querySelector("input[name='date']").value;
        let slot = document.querySelector("select[name='slot']").value;
        const statusEl = document.getElementById("slot_status");

        if (doctor && date && slot) {
            fetch(`../backend/check_slot.php?doctor=${doctor}&date=${date}&slot=${slot}`)
                .then(res => res.text())
                .then(data => {
                    if (data === "available") {
                        statusEl.innerText = "✅ Slot Available";
                        statusEl.style.color = "green";
                    } else {
                        statusEl.innerText = "❌ Slot Taken";
                        statusEl.style.color = "red";
                    }
                });
        }
    }
});


