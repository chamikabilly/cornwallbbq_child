(function ($) {
  $(document).ready(function () {
    function showToast(type, message) {
      // If a toast system exists, trigger it; otherwise fallback to alert
      if (window.dispatchEvent) {
        try {
          const event = new CustomEvent("miheli:toast", {
            detail: { type: type, message: message },
          });
          window.dispatchEvent(event);
          return;
        } catch (e) {}
      }
      // Fallback
      if (type === "success") {
        console.log(message);
      } else {
        console.error(message);
      }
    }

    $(".manage-orders-table").on("click", ".js-update-status", function () {
      var $btn = $(this);
      var $row = $btn.closest("tr");
      var orderId = parseInt($row.data("order-id"), 10);
      var newStatus = $row.find(".status-select").val();

      if (!orderId || !newStatus) {
        showToast("error", "Invalid order or status.");
        return;
      }

      $btn.prop("disabled", true).text("Updating...");

      $.ajax({
        url:
          typeof miheliManageOrders !== "undefined"
            ? miheliManageOrders.ajaxurl
            : "/wp-admin/admin-ajax.php",
        method: "POST",
        dataType: "json",
        data: {
          action: "miheli_update_order_status",
          nonce:
            typeof miheliManageOrders !== "undefined"
              ? miheliManageOrders.nonce
              : "",
          order_id: orderId,
          new_status: newStatus,
        },
      })
        .done(function (res) {
          if (res && res.success && res.data) {
            var label = res.data.label || newStatus;
            $row.find(".js-status-label").text(label);
            showToast(
              "success",
              "Order #" + orderId + " updated to " + label + ".",
            );
          } else {
            var msg =
              res && res.data && res.data.message
                ? res.data.message
                : "Update failed";
            showToast("error", msg);
          }
        })
        .fail(function (xhr) {
          var msg = "Request failed";
          if (
            xhr &&
            xhr.responseJSON &&
            xhr.responseJSON.data &&
            xhr.responseJSON.data.message
          ) {
            msg = xhr.responseJSON.data.message;
          }
          showToast("error", msg);
        })
        .always(function () {
          $btn.prop("disabled", false).text("Update");
        });
    });
  });
})(jQuery);
