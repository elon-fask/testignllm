import React from 'react';

/* eslint-disable jsx-a11y/label-has-for */
const NcccoFeesSection = props => {
  return (
    <div className="col-xs-6">
      <div className="form-group">
        <label className="control-label">Written NCCCO Testing Services Fee</label>
        <div className="input-group">
          <span className="input-group-addon">$</span>
          <input
            type="text"
            defaultValue={parseFloat(props.writtenNcccoFees).toFixed(2)}
            className="form-control"
            aria-label="Written NCCCO Testing Services Fee"
            readOnly
          />
        </div>
        <div className="help-block" />
      </div>
      <div className="form-group">
        <label htmlFor="app-type" className="control-label">
          Written NCCCO Fee Override
        </label>
        <div className="input-group">
          <span className="input-group-addon">$</span>
          <input
            type="text"
            defaultValue={props.writtenNcccoFeeOverride ? parseFloat(props.writtenNcccoFeeOverride).toFixed(2) : null}
            className="form-control"
            name="Candidates[written_nccco_fee_override]"
          />
        </div>
        <div className="help-block" />
      </div>
      <div className="form-group">
        <label htmlFor="app-type" className="control-label">
          Practical NCCCO Testing Services Fee
        </label>
        <div className="input-group">
          <span className="input-group-addon">$</span>
          <input
            type="text"
            defaultValue={parseFloat(props.practicalNcccoFees).toFixed(2)}
            className="form-control practical-iai-fee"
            readOnly
          />
        </div>
        <div className="help-block" />
      </div>
      <div className="form-group">
        <label htmlFor="app-type" className="control-label">
          Practical NCCCO Fee Override
        </label>
        <div className="input-group">
          <span className="input-group-addon">$</span>
          <input
            type="text"
            defaultValue={
              props.practicalNcccoFeeOverride ? parseFloat(props.practicalNcccoFeeOverride).toFixed(2) : null
            }
            className="form-control"
            name="Candidates[practical_nccco_fee_override]"
          />
        </div>
        <div className="help-block" />
      </div>
    </div>
  );
};

export default NcccoFeesSection;
