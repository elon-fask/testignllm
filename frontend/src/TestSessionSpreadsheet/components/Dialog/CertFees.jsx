import React from 'react';
import Checkbox from 'material-ui/Checkbox';

const CertFees = ({ checkedFees, preCheckEdit, addCheckedFee, removeCheckedFee }) => (
  <div style={{ marginTop: '20px' }}>
    <div style={{ fontWeight: 'bold' }}>Written Certification Fees</div>
    <div style={{ display: 'flex' }}>
      <div>
        <span>Mobile Crane Exams</span>
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
          label="Core Exam plus one Specialty Exam"
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
      </div>
      <div>
        <span>Retest or Added Specialty Fees</span>
        <Checkbox
          checked={checkedFees.includes('W_FEE_ADDED_CORE')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_ADDED_CORE');
            } else {
              removeCheckedFee('W_FEE_ADDED_CORE');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Core Exam only or Core plus one Specialty (Retest)"
        />
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
          label="One Specialty Exam (Retest or Added Specialty)"
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
          label="Two Specialty Exams (Retest or Added Specialty)"
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
          label="Three Specialty Exams (Retest or Added Specialty)"
        />
        <Checkbox
          checked={checkedFees.includes('W_FEE_ADDED_SPECIALTY_4')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_ADDED_SPECIALTY_4');
            } else {
              removeCheckedFee('W_FEE_ADDED_SPECIALTY_4');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Four Specialty Exams (Retest)"
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
        <Checkbox
          checked={checkedFees.includes('W_FEE_UPDATE_REPLACE')}
          onCheck={(e, isChecked) => {
            if (isChecked) {
              addCheckedFee('W_FEE_UPDATE_REPLACE');
            } else {
              removeCheckedFee('W_FEE_UPDATE_REPLACE');
            }
          }}
          labelStyle={{ fontWeight: 'normal' }}
          label="Updated/Replacement Card"
        />
      </div>
    </div>
  </div>
);

export default CertFees;
