function initRepairGet() {
  var o = {
    get: function() {
      return "id=" + $E("id").value + "&" + this.name + "=" + this.value;
    },
    onSuccess: function() {
      equipment.valid();
      serial.valid();
    },
    onChanged: function() {
      $E("inventory_id").value = 0;
      equipment.reset();
      serial.reset();
    }
  };
  var equipment = initAutoComplete(
    "equipment",
    WEB_URL + "index.php/inventory/model/autocomplete/find",
    "equipment,serial",
    "find",
    o
  );
  var serial = initAutoComplete(
    "serial",
    WEB_URL + "index.php/inventory/model/autocomplete/find",
    "serial,equipment",
    "find",
    o
  );
}
