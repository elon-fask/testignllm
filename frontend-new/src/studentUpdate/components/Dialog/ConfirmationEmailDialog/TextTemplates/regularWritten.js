export default function getRegularWrittenText(values) {
  return `<p>Thank you for enrolling in    ${
    values.school
  }&#39;s Crane Operator Certification program. This email confirms the receipt of your application for the ${
    values.classDates
  } class in ${values.city}.</p>
  <p>The following information is provided in order to give you a better idea of where the class will be held, what you&#39;ll need to bring with you, and which topics will be covered in the class.</p>
  <ul>
  <li>If the training/testing has not been paid for in full, all remaining testing fees will be due on the first day of class. We accept checks, credit and debit cards, and cashiers check, or cash.</li>
  </ul>
  <p><strong>Class Time & Date</strong></p>
  <p>Note: Please do not arrive prior to ${values.startTime}</p>
  <ul>
${values.classSchedule.reduce((acc, schedule) => `${acc}<li>${schedule}</li>`, '')}
  </ul>
  <p><strong>Class & Written Exam Address<br>
  ${values.siteName}<br>
  ${values.address}<br>
  ${values.city}, ${values.state} ${values.zip}
  </strong></p>
  <p>Pens, pencils, highlighters and notepads will be provided. All handouts (including study guides and load charts) will also be provided at the time of the class. Load charts used during the class may be downloaded.</p>
  <p><a href="http://www.californiacraneschool.com/crane_study_material.php">http://www.californiacraneschool.com/crane_study_material.php</a></p>
  <p><strong>Exam Overview:</strong><br>
  The Written Exams will vary based on whether you're a first time candidate, or pursuing recertification.</p>
  <p><strong>Core Test New</strong><br>
  Certification Candidates: consists of 90 multiple choice questions (maximum of 90 minutes to complete).<br>
  Recertification Candidates: consists of 40 multiple choice questions (maximum of 40 minutes to complete).</p>
  <p><strong>Specialty Exams</strong><br>
  New Certification Candidates: consists of a 26 question Specialty Exam related to the Manitex 1768 (small telescopic boom crane), and a 26 question Specialty Exam related to the Link-Belt RTC 8050 (large telescopic boom crane). Candidates will have 55 minutes to complete each test.<br>
  Recertification Candidates: consists of (two) 10 question tests. Candidates will have 25 minutes to complete each test.
  </p>`;
}
