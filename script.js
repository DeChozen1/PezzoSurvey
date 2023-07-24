const form = document.querySelector('#survey-form');

function submitForm(event) {
  event.preventDefault();

  const request = new XMLHttpRequest();

  // Instantiating the request object
  request.open("POST", "/submit.php");

  // Defining event listener for readystatechange event
  request.onreadystatechange = function() {
    // Check if the request is compete and was successful
    if(this.readyState === 4 && this.status === 200) {
      console.log(this.responseText);
      const data = JSON.parse(this.responseText);

      if (data.error.length > 0) {
        alert(data.error);
      }

      if (data.success.length > 0) {
        alert(data.success);
        form.reset();

        fetchData();
      }
    }
  };

  // Retrieving the form data
  const myForm = document.getElementById("survey-form");
  const formData = new FormData(myForm);

  // Sending the request to the server
  request.send(formData);
}

// Attach event listener to the form submit event
form.addEventListener('submit', submitForm);


fetchData();

function fetchData() {
  const request = new XMLHttpRequest();

  // Instantiating the request object
  request.open("POST", "/leaderboard.php");

  // Defining event listener for readystatechange event
  request.onreadystatechange = function() {
    // Check if the request is compete and was successful
    if(this.readyState === 4 && this.status === 200) {
      document.getElementById('table-data').innerHTML = this.responseText;
    }
  };

  const formData = new FormData();
  formData.append('all_data', true);

  request.send(formData);
}