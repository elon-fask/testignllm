export default function getPracticalOnlyText(values) {

  return `<p>This is to confirm that you are scheduled to take a practical exam on the <strong>${
    values.craneExam
  }</strong> in <strong>${values.city}</strong> on <strong>${
    values.examSchedule
  }</strong>. Please call or e-mail if you need to reschedule because we will have an examiner waiting in the yard. Tests are available monthly at our <strong>${
    values.city
  }</strong> location.</p><p>You will need to show up a half hour early to watch the NCCCO Mobile Crane Operator Practical Exam
  Video. NOTE: If you watch the video prior to the morning of the test, you will need to refresh by
  watching it again the morning of your test to be in compliant with NCCCO&#39;s guidelines.</p><p>Here is a link for your convenience to watch via your phone: <a href="https://vimeo.com/104640886">https://vimeo.com/104640886</a></p><p>Practical Exam Location: <strong><a href="${
    values.mapUrl
  }">(Click here to view map)</a><br>${values.siteName}<br>${values.address}<br>${values.city}, ${values.state} ${
    values.zip
  }</strong></p><p>Candidates should bring their hardhats and work boots to their Practical Exam. Likewise, any time spent in the training yard practicing on, or operating our cranes, you will be required to wear a hard hat and work boots.</p><p>Each candidate will be given a 20 minute familiarization period with each crane prior to their actual exam. Additional 1-on-1 training time with a certified instructor is available at a rate of $150 per hour.</p>`;
}
