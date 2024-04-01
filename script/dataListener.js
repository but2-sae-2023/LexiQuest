async function getData(file) {
	try {
		const response = await fetch('../backend/' + file);
		const data = await response.json();
		console.log(data);
		return data;
	} catch (error) {
		console.error(error);
	}
}

async function sendData(file, formId) {
	document.getElementById(formId).addEventListener("submit", function(event) {
		event.preventDefault();

		let data = new FormData(this);
		let xhr = new XMLHttpRequest();

		xhr.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				// console.log(this.response);
				window.location.reload();
			} else if (this.readyState == 4) {
				alert("Une erreur est survenue...");
			}
		}

		xhr.open("POST", "../backend/" + file, true);
		xhr.send(data);
		return false;
	});
}