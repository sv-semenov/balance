const submit_btn = document.getElementById("submit");
const data_table = document.getElementById("data");

submit_btn.onclick = function (e) {
  e.preventDefault();
  data_table.style.display = "block";

  var a = document.getElementById("user");
  var user = a.value;
  var name = a.options[a.selectedIndex].text;
   document.querySelector(".name-user").innerText=name
  // AJAX Get transactions balances
 let xhr = new XMLHttpRequest();
  xhr.open('GET', 'data.php?user=' +user, true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      let data = xhr.responseText;
      let table = JSON.parse(data);
      document.querySelectorAll("#table .row").forEach(function(item) {
        item.remove();
      });
      var eventTable = document.getElementById("table");
      table.forEach(function(item) {
       let row = document.createElement("tr");
       row.classList.add('row');
       row.innerHTML = `
        <td>${item.month}.${item.year}</td>
        <td>${item.balans}</td>
        <td>${item.count}</td>
       `;
       eventTable.appendChild(row);
      });
      
    } else {
      console.error('Error:', xhr.statusText);
    }
  };
  xhr.send();
};


