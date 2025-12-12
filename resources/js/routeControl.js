function initroute() {
  document
    .getElementById("routeSubmitBtn")
    .addEventListener("click", async () => {
      console.log("routebutton clicked");
      const documentId = document.getElementById("docId").value;
      const destinationOffice =
        document.getElementById("routeOfficeSelect").value;
      const recipientUserId = document.getElementById("routeUserSelect").value;
      const approvalType = document.getElementById("routeApprovalSelect").value;
      const routeStatusSelect =
        document.getElementById("routeStatusSelect").value;
      const remarks = document.getElementById("routeRemarks").value;
      const routedPdfFile = document.getElementById("routefileInput");
      if (routedPdfFile.files && routedPdfFile.files.length > 0) {
        // console.log("File is selected");
      } else {
        // console.log("No file selected");
      }

      try {
        const formData = new FormData();
        formData.append("document_id", documentId);
        formData.append("destination_office", destinationOffice);
        formData.append("recipient_user_id", recipientUserId);
        formData.append("approval_type", approvalType);
        formData.append("status", routeStatusSelect);
        formData.append("remarks", remarks);

        if (routedPdfFile.files.length > 0) {
          formData.append("pdf_file", routedPdfFile.files[0]);
        }

        const res = await fetch("/api/documents/route", {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
              .content,
          },
          body: formData,
        });

        const data = await res.json();
        if (res.ok) {
          //   console.log(data);
          window.getDocs();
          const routingmodal = document.getElementById("routingModal");
          routingmodal.classList.add("hidden");
          const documentmodal = document.getElementById("DocumentModal");
          documentmodal.classList.add("hidden");

          showMessage({
            status: "success",
            message: "Routing Success",
          });
        }
      } catch (err) {
        console.error(err);
        alert("Failed to route document.");
      }
    });
}
window.initroute = initroute;
