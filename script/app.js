document.getElementById("form").addEventListener("submit", function(event) {
	event.preventDefault();

	let data = new FormData(this);

	let xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			// console.log(this.response); 
		} else if (this.readyState == 4) {
			alert("Une erreur est survenue...");
		}
	}

	xhr.open("POST", "../backend/game.php", true);
	xhr.send(data);
	return false;
});