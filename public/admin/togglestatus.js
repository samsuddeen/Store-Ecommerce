function changeStatus(table, id, selector) {
    var confirmation = confirm("are you sure?");
    return confirmation
        ? axios
              .post("toggle-status", {
                  table: table,
                  id: id,
              })
              .then((response) => {
                  toastr.success("status changed successfully");
                  $(selector).DataTable().draw();
              })
              .catch((error) => {
                  reject(error);
              })
        : null;
}
