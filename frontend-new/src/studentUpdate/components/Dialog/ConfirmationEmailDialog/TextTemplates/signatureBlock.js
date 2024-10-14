export default function getSignatureBlockText(values) {
  return `<br><p> Please feel free to contact us with questions via phone or email.</p>
  <p><strong>${values.senderName}<br>
  ${values.school}<br>
  ${values.senderAddress}<br>
  ${values.senderCity}, ${values.senderState} ${values.senderZip}</strong><br>
  Telephone: <strong>${values.senderPhone}</strong><br>
  Fax: <strong>${values.senderFax}</strong><br>
  Email: <strong>${values.senderEmail}</strong></p>
  `;
}
