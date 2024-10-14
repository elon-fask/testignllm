import React from 'react';
import Checkbox from 'material-ui/Checkbox';

const RecertFees = ({ checkedFees, preCheckEdit, addCheckedFee, removeCheckedFee }) => (
  <div style={{ marginTop: '20px' }}>
    <div style={{ fontWeight: 'bold' }}>Written Recertification Fees</div>
    <div style={{ display: 'flex' }}>
      <div>
        <span>Recertification Exam Fees/Retest Fees</span>
        <Checkbox
          checked={checkedFees.includes('W_FEE_CORE_1')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_CORE_1');
            } else {
              removeCheckedFee('W_FEE_CORE_1');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Mobile Core Exam plus one Specialty Exam"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_CORE_2')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_CORE_2');
            } else {
              removeCheckedFee('W_FEE_CORE_2');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Core Exam plus two Specialty Exams"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_CORE_3')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_CORE_3');
            } else {
              removeCheckedFee('W_FEE_CORE_3');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Core Exam plus three Specialty Exams"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_CORE_4')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_CORE_4');
            } else {
              removeCheckedFee('W_FEE_CORE_4');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Core Exam plus four Specialty Exams"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_TOWER')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_TOWER');
            } else {
              removeCheckedFee('W_FEE_TOWER');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Tower Crane (only)"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_TOWER_W_MOBILE')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_TOWER_W_MOBILE');
            } else {
              removeCheckedFee('W_FEE_TOWER_W_MOBILE');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Tower Crane (with Mobile Crane)"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_OVERHEAD')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_OVERHEAD');
            } else {
              removeCheckedFee('W_FEE_OVERHEAD');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Overhead Crane (only)"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_OVERHEAD_W_MOBILE')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_OVERHEAD_W_MOBILE');
            } else {
              removeCheckedFee('W_FEE_OVERHEAD_W_MOBILE');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Overhead Crane (with Mobile Crane)"
        />
      </div>
      <div>
        <span>Additional Exam Fees</span>
        <Checkbox
          checked={checkedFees.includes('W_FEE_ADDED_SPECIALTY_1')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_ADDED_SPECIALTY_1');
            } else {
              removeCheckedFee('W_FEE_ADDED_SPECIALTY_1');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="One Mobile Specialty Exam"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_ADDED_SPECIALTY_2')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_ADDED_SPECIALTY_2');
            } else {
              removeCheckedFee('W_FEE_ADDED_SPECIALTY_2');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Two Mobile Specialty Exam"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_ADDED_SPECIALTY_3')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_ADDED_SPECIALTY_3');
            } else {
              removeCheckedFee('W_FEE_ADDED_SPECIALTY_3');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Three Mobile Specialty Exam"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_ADDED_TOWER')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_ADDED_TOWER');
            } else {
              removeCheckedFee('W_FEE_ADDED_TOWER');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Tower Crane Exam"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_ADDED_OVERHEAD')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_ADDED_OVERHEAD');
            } else {
              removeCheckedFee('W_FEE_ADDED_OVERHEAD');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Overhead Crane Exam"
        />
      </div>
      <div>
        <span>Other Fees</span>
        <Checkbox
          checked={checkedFees.includes('W_FEE_LATE')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              preCheckEdit('W_FEE_LATE', true);
            } else {
              preCheckEdit('W_FEE_LATE', false);
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Candidate Late Fee (if applicable)"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_INCOMPLETE')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              preCheckEdit('W_FEE_INCOMPLETE', true);
            } else {
              preCheckEdit('W_FEE_INCOMPLETE', false);
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Incomplete Application Fee (if applicable)"
        />
      </div>
    </div>
  </div>
);

export default RecertFees;
