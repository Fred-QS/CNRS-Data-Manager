window.onload = function() {
    setAges();
}

function setAges() {

    let birthDates = document.querySelectorAll('.age_code');
    for (let i = 0; i < birthDates.length; i++) {

        let d = birthDates[i].textContent.trim(),
            split = d.split('-'),
            date = new Date(split[0], split[1], split[2]),
            parent = birthDates[i].parentElement,
            insert = parent.querySelector('.age_insert p');

        if (d.length > 0) {
            let age = calculateAge(date);
            insert.innerHTML = age + ' ans, ' + insert.innerHTML;
        }
    }
}

function calculateAge(birthday) {

    var ageDifMs = Date.now() - birthday.getTime();
    var ageDate = new Date(ageDifMs);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}