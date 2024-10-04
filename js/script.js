let number = 0
let amount = 1

document.getElementById("listButton").addEventListener("click", function () {
  fetch("fetch_data.php")
    .then((response) => response.json())
    .then((data) => {
      const tableBody = document.querySelector("#productTable tbody")
      tableBody.innerHTML = ""
      data.forEach((row) => {
        const tr = document.createElement("tr")
        tr.setAttribute("data-number", row.number)
        tr.innerHTML = `
                    <td data-number="${row.number}">${row.number}</td>
                    <td>${row.name}</td>
                    <td>${row.bottle_size}</td>
                    <td>${row.price}</td>
                    <td>${row.price_gbp}</td>
                    <td>${row.time_stamp}</td>
                    <td>${row.order_amount}</td>
                    <td>
                        <button class="addAmount" data-number="${row.number}" data-amount="${row.order_amount}">Add</button>
                        <button class="removeAmount" data-number="${row.number}" data-amount="${row.order_amount}">Clear</button>
                    </td>
                `
        tableBody.appendChild(tr)
      })
      attachEventListeners()
    })
    .catch((error) => console.error("Error fetching data:", error))
})

// Function to attach event listeners
function attachEventListeners() {
  // Add event listeners
  document.querySelectorAll(".addAmount").forEach((button) => {
    button.addEventListener("click", function () {
      if (this.dataset.number != number) {
        number = this.dataset.number
        if (!amount) {
          amount = parseInt(this.dataset.amount) + 1
        } else {
          amount = parseInt(this.dataset.amount)
        }
      } else {
        amount += 1
      }
      updateOrderAmount(number, amount)
    })
  })

  // Clear event listeners
  document.querySelectorAll(".removeAmount").forEach((button) => {
    button.addEventListener("click", function () {
      if (this.dataset.number != number) {
        number = this.dataset.number
        amount = parseInt(this.dataset.amount)
      } else {
        if (amount) {
          amount -= 1
        }
      }
      updateOrderAmount(number, amount)
    })
  })
}

// Function to update order amount
function updateOrderAmount(number, amount) {
  fetch("update_order.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ number: number, orderAmount: amount }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        const orderAmountCell = document.querySelector(
          `tr[data-number='${number}'] td:nth-child(7)`
        )
        orderAmountCell.textContent = amount
      } else {
        alert("Failed to update order amount.")
      }
    })
    .catch((error) => console.error("Error updating order amount:", error))
}

document.getElementById("emptyButton").addEventListener("click", function () {
  fetch("empty_table.php", { method: "POST" })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Table emptied successfully!")
        document.querySelector("#productTable tbody").innerHTML = ""
      } else {
        alert("Failed to empty table.")
      }
    })
    .catch((error) => console.error("Error emptying table:", error))
})
