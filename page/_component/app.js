// APP REUSABLE COMPONENTS

// CUSTOMIZE
$(".btn").addClass("fw-bold");
$(".btn").css("text-transform", "uppercase");
$(".app__btn-primary").addClass("btn-primary");

// PLUGINS
new DataTable("#dataTable", {
  scrollX: true,
  layout: {
    topStart: {
      buttons: ["excel", "pdf", "colvis"],
    },
  },
});
