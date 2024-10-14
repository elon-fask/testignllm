import React from 'react';
import { viewTypes } from '../../reducers/ui';

const HeaderUpper = ({ view }) => {
  const mapping = {
    [viewTypes.DEFAULT]: (
      <tr className="tableHeader--upper">
        <td colSpan={1} style={{ border: 'none', background: 'none' }} />
        <td colSpan={3}>WRITTEN</td>
        <td colSpan={2}>PRACTICAL</td>
        <td colSpan={6}>NCCCO Fees</td>
        <td colSpan={8} style={{ border: 'none', background: 'none' }} />
        <td colSpan={3}>Grades Written</td>
        <td colSpan={2}>Grades Practical</td>
      </tr>
    ),
    [viewTypes.PRACTICAL_TEST_SCHEDULE]: (
      <tr className="tableHeader--upper">
        <td colSpan={1} style={{ borderTop: '1px solid #fff', borderRight: 'none', background: 'none' }} />
      </tr>
    ),
    [viewTypes.GRADING]: (
      <tr className="tableHeader--upper">
        <td style={{ border: 'none', background: 'none' }} />
        <td colSpan={3}>Grades Written</td>
        <td colSpan={2}>Grades Practical</td>
      </tr>
    ),
    [viewTypes.CLASSREADINESS]: (
      <tr className="tableHeader--upper">
        <td colSpan={1} style={{ border: 'none', background: 'none' }} />
        <td colSpan={3}>WRITTEN</td>
        <td colSpan={2}>PRACTICAL</td>
      </tr>
    ),
    [viewTypes.CANDIDATE_CHECKLIST]: (
      <tr className="tableHeader--upper">
        <td colSpan={4}>Candidate Checklist</td>
      </tr>
    ),
    [viewTypes.NOGRADES]: (
      <tr className="tableHeader--upper">
        <td colSpan={1} style={{ border: 'none', background: 'none' }} />
        <td colSpan={3}>WRITTEN</td>
        <td colSpan={2}>PRACTICAL</td>
        <td colSpan={7}>NCCCO Fees</td>
      </tr>
    ),
    [viewTypes.BOOKKEEPING]: (
      <tr className="tableHeader--upper">
        <td colSpan={1} style={{ border: 'none', background: 'none' }} />
        <td colSpan={7}>NCCCO Fees</td>
      </tr>
    ),
    [viewTypes.BOOKKEEPING_BACKLOG]: (
      <tr className="tableHeader--upper">
        <td style={{ border: 'none', background: 'none' }} />
      </tr>
    ),
    [viewTypes.APPFORMS]: (
      <tr className="tableHeader--upper">
        <td colSpan={1} style={{ border: 'none', background: 'none' }} />
        <td colSpan={3}>WRITTEN</td>
        <td colSpan={2}>PRACTICAL</td>
      </tr>
    ),
    [viewTypes.CUSTOM]: (
      <tr className="tableHeader--upper">
        <td style={{ border: 'none', background: 'none' }} />
      </tr>
    )
  };

  return mapping[view];
};

export default HeaderUpper;
