function initRepairGet() {
  var o = {
    get: function() {
      return "id=" + $E("id").value + "&" + this.name + "=" + this.value;
    },
    onSuccess: function() {
      equipment.valid();
      serial.valid();
      equipment_number.valid();
    },
    onChanged: function() {
      $E("inventory_id").value = 0;
      equipment.reset();
      serial.reset();
      equipment_number.reset();
    }
  };
  var equipment = initAutoComplete(
    "equipment",
    WEB_URL + "index.php/inventory/model/autocomplete/find",
    "equipment,serial,equipment_number",
    "find",
    o
  );
  var serial = initAutoComplete(
    "serial",
    WEB_URL + "index.php/inventory/model/autocomplete/find",
    "serial,equipment,equipment_number",
    "find",
    o
  );
  var equipment_number = initAutoComplete(
    "equipment_number",
    WEB_URL + "index.php/inventory/model/autocomplete/find",
    "equipment_number,equipment,toll_id",
    "find",
    o
  );
}
