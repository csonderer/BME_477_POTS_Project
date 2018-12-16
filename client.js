/**
 * Source code based on example code provided by SMART Health IT
 */

// Use after authorization success
// The authorization process (see launch.html) provided an instance of the 
// FHIR.client (called 'smart'), which will be used below
// NOTE: the scope of the 'smart' instance is limited to this function 
// because the details of the launch, luanch scope, and client are passed to FHIR.oauth2.ready
FHIR.oauth2.ready(function(smart) {
	smart.patient.read().then(function(pt) {
		displayPatient(pt);
	});

	smart.patient.api.fetchAllWithReferences({type: "MedicationOrder"},["MedicationOrder.medicationReference"]).then(function(results, refs) {
		results.forEach(function(prescription){
			if (prescription.medicationCodeableConcept && prescription.status == "active") { // Only want medications that are currently being used
				displayMedication(prescription.medicationCodeableConcept.coding);
			} 
			else if (prescription.medicationReference) {
				var med = refs(prescription, prescription.medicationReference);
				displayMedication(med && med.code.coding || []);
			}
		});
	});   	

}); 	 

function getPatientName (pt) {
	if (pt.name) {
		var names = pt.name.map(function(name) {
			return name.given.join(" ") + " " + name.family.join(" ");
		});
		return names.join(" / ")
	} else {
		return "anonymous";
	}
}

function getMedicationName (medCodings) {
	var coding = medCodings.find(function(c){
		return c.system == "http://www.nlm.nih.gov/research/umls/rxnorm";
	});

	return coding && coding.display || "Unnamed Medication(TM)"
}

function displayPatient (pt) {
	document.getElementById('name').innerHTML = getPatientName(pt);
}

var med_list = document.getElementById('med_list');

function displayMedication (medCodings) {
    med_list.innerHTML += "<li> " + getMedicationName(medCodings) + "</li>";
}