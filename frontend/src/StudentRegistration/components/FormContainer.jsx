import React from 'react';

const FormContainer = props => (
  <section className="form-container">
    <div className="clearfix">
      <div className="form-container--title">{props.title}</div>
    </div>
    <div className="form-container--content">{props.children}</div>
  </section>
);

export default FormContainer;
