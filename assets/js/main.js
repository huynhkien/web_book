// alert("đã kết nối");
// JavaScript để cắt văn bản và thêm dấu "..."
var titleElements = document.getElementsByClassName("productTitle");
var maxLength = 35; // Độ dài tối đa
for (var i = 0; i < titleElements.length; i++) {
  var titleElement = titleElements[i];
  var titleText = titleElement.innerText;

  if (titleText.length > maxLength) {
    titleElement.innerText = titleText.substring(0, maxLength) + "...";
  }
}

// live_search
$(document).ready(function () {
  $("#live_search").keyup(function () {
    var input = $(this).val();

    if (input !== "") {
      $.ajax({
        url: "../partials/live_search.php",
        method: "POST",
        data: {
          input: input,
        },
        success: function (data) {
          console.log("Response:", data);
          $("#searchresult").html(data);
          $("#searchresult").css("display", "block");
        },
      });
    } else {
      $("#searchresult").css("display", "none");
    }
  });
});
// tăng giảm
function increment() {
  console.log("Increment is called");
  var quantityInput = document.getElementById("quantity");
  var currentQuantity = parseInt(quantityInput.value, 10);
  quantityInput.value = currentQuantity + 1;
}

function decrement() {
  console.log("Decrement is called");
  var quantityInput = document.getElementById("quantity");
  var currentQuantity = parseInt(quantityInput.value, 10);

  // Giảm số lượng chỉ khi nó lớn hơn 1
  if (currentQuantity > 1) {
    quantityInput.value = currentQuantity - 1;
  }
}

//jquery validate
$(document).ready(function () {
  $("#cart_form").validate({
    rules: {
      name: {
        required: true,
        minlength: 2,
      },
      phone: {
        required: true,
        minlength: 10,
      },
      address: "required",
    },
    messages: {
      name: {
        required: "Bạn chưa nhập tên",
        minlength: "Tên phải có ít nhất 2 ký tự",
      },
      phone: {
        required: "Bạn chưa nhập số điện thoại",
        minlength: "Số điện thoại phải có ít nhất 10 số",
      },
      address: "Bạn chưa nhập địa chỉ",
    },
    errorElement: "div",
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      if (element.prop("type") === "checkbox") {
        error.insertAfter(element.siblings("label"));
      } else {
        error.insertAfter(element);
      }
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).addClass("is-valid").removeClass("is-invalid");
    },
  });
});

$(document).ready(function () {
  $("#example").DataTable();
});
