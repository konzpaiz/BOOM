function muatStatusSepeda() {
  var grid = document.querySelector(".bike-grid");
  if (!grid) {
    return; 
  }

  fetch("api/get_bikes.php")
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      var sepedaTersedia = data.records || [];
      var cards = document.querySelectorAll(".bike-card");

      cards.forEach(function (card) {
        var namaDiv = card.querySelectorAll("div")[1];
        if (!namaDiv) return;
        var merkCard = namaDiv.textContent.trim();

        var masihTersedia = sepedaTersedia.some(function (s) {
          return s.merk === merkCard;
        });

        var badge = card.querySelector(".bike-status .badge");
        if (!badge) return;

        if (masihTersedia) {
          badge.textContent = "Tersedia";
          badge.className = "badge badge-green";
        } else {
          badge.textContent = "Sedang Disewa";
          badge.className = "badge badge-yellow";
        }
      });
    })
    .catch(function (error) {
      console.log("Gagal memuat status sepeda:", error);
    });
}

document.addEventListener("DOMContentLoaded", function () {
  muatStatusSepeda();
});
