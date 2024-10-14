import React, { Component } from 'react';
import MUIDialog from 'material-ui/Dialog';
import FlatButton from 'material-ui/FlatButton';
import RaisedButton from 'material-ui/RaisedButton';
import { RadioButton, RadioButtonGroup } from 'material-ui/RadioButton';
import { formFieldText } from '../../../common/applicationForms';
import { applicationFormsToGrades, gradeValues, gradeLabelValues } from '../../../common/grades';

const splitGrades = grades =>
  Object.keys(grades).reduce(
    (acc, gradeKey) => {
      if (gradeKey[0] === 'W') {
        return [
          {
            ...acc[0],
            [gradeKey]: grades[gradeKey]
          },
          acc[1]
        ];
      }
      return [
        acc[0],
        {
          ...acc[1],
          [gradeKey]: grades[gradeKey]
        }
      ];
    },
    [{}, {}]
  );

const splitFields = fields =>
  fields.reduce(
    (acc, field) => {
      if (field[0] === 'W') {
        return [[...acc[0], field], acc[1]];
      }
      return [acc[0], [...acc[1], field]];
    },
    [[], []]
  );

const convertGradesToValues = grades =>
  Object.keys(grades).reduce((acc, key) => ({ ...acc, [key]: gradeLabelValues[grades[key]] }), {});

const gradeBoxStyleBase = {
  padding: '8px',
  textAlign: 'center',
  width: '120px',
  color: '#fff'
};

const gradeBoxStyle = {
  Pass: {
    ...gradeBoxStyleBase,
    backgroundColor: 'green'
  },
  Fail: {
    ...gradeBoxStyleBase,
    backgroundColor: 'red'
  },
  'Did Not Test': {
    ...gradeBoxStyleBase,
    backgroundColor: 'yellow',
    color: '#000'
  },
  SD: {
    ...gradeBoxStyleBase,
    backgroundColor: 'red'
  }
};

export default class GradesDialog extends Component {
  constructor(props) {
    super(props);

    const formSetup = { ...this.props.applicationType.formSetup, ...this.props.candidate.customFormSetup };
    const [writtenTests, practicalExams] = splitFields(applicationFormsToGrades(formSetup));
    const [givenGradesWritten, givenGradesPractical] = splitGrades(this.props.candidate.grades);

    this.state = {
      gradesWritten: writtenTests.reduce((acc, writtenTest) => {
        if (typeof givenGradesWritten[writtenTest] === 'undefined') {
          return {
            ...acc,
            [writtenTest]: null
          };
        }
        return acc;
      }, {}),
      gradesPractical: practicalExams.reduce((acc, practicalExam) => {
        if (typeof givenGradesPractical[practicalExam] === 'undefined') {
          return {
            ...acc,
            [practicalExam]: null
          };
        }
        return acc;
      }, {}),
      writtenTests,
      practicalExams,
      givenGradesWritten,
      givenGradesPractical,
      isResetLoading: false
    };
  }

  setGrade = (isWritten, test, value) => {
    const key = isWritten ? 'gradesWritten' : 'gradesPractical';

    const newState = {
      [key]: {
        ...this.state[key],
        [test]: value
      }
    };

    this.setState(newState);
  };

  editSavedGrades = () => {
    this.setState({
      givenGradesWritten: {},
      givenGradesPractical: {},
      gradesWritten: {
        ...convertGradesToValues(this.state.givenGradesWritten),
        ...this.state.gradesWritten
      },
      gradesPractical: {
        ...convertGradesToValues(this.state.givenGradesPractical),
        ...this.state.gradesPractical
      }
    });
  };

  resetGrades = () => {
    this.setState(
      {
        isResetLoading: true,
        givenGradesWritten: {},
        givenGradesPractical: {},
        gradesWritten: this.state.writtenTests.reduce(
          (acc, test) => ({
            ...acc,
            [test]: null
          }),
          {}
        ),
        gradesPractical: this.state.practicalExams.reduce(
          (acc, exam) => ({
            ...acc,
            [exam]: null
          }),
          {}
        )
      },
      () => {
        this.props
          .resetCandidateGrades(this.props.candidate.id)
          .then(() => {
            this.setState({
              isResetLoading: false
            });
          })
          .catch(e => {
            console.error(e);
            this.setState({
              isResetLoading: false
            });
          });
      }
    );
  };

  handleSubmit = () => {
    const convertedGivenGradesW = convertGradesToValues(this.state.givenGradesWritten);
    const convertedGivenGradesP = convertGradesToValues(this.state.givenGradesPractical);

    const setGradesW = Object.keys(this.state.gradesWritten).reduce((acc, key) => {
      const grade = this.state.gradesWritten[key];
      if (grade !== null) {
        return {
          ...acc,
          [key]: grade
        };
      }
      return acc;
    }, {});

    const setGradesP = Object.keys(this.state.gradesPractical).reduce((acc, key) => {
      const grade = this.state.gradesPractical[key];
      if (grade !== null) {
        return {
          ...acc,
          [key]: grade
        };
      }
      return acc;
    }, {});

    const gradesWritten = { ...convertedGivenGradesW, ...setGradesW };
    const gradesPractical = { ...convertedGivenGradesP, ...setGradesP };

    this.props.blurCell(this.props.candidate.id, { ...gradesWritten, ...gradesPractical }, 22);
  };

  render() {
    const actions = [
      <FlatButton label="Cancel" style={{ marginRight: '20px' }} primary onTouchTap={this.props.closeDialog} />,
      <RaisedButton label="Save" primary onTouchTap={this.handleSubmit} />
    ];

    const hasGivenGrades =
      Object.keys(this.state.givenGradesWritten).length + Object.keys(this.state.givenGradesPractical).length > 0;

    return (
      <MUIDialog
        title={`Grades - ${this.props.candidate.name}`}
        actions={actions}
        modal
        open={this.props.isOpen}
        autoScrollBodyContent
      >
        <div style={{ marginBottom: '20px' }}>
          <a href={`/admin/candidates/update?id=${this.props.candidate.idHash}`}>Go to Candidate Application Page</a>
        </div>
        {hasGivenGrades && (
          <div style={{ marginBottom: '20px' }}>
            <RaisedButton
              label="Edit Saved Grades"
              primary
              onTouchTap={this.editSavedGrades}
              style={{ marginRight: '40px' }}
            />
            <RaisedButton label="Reset Grades" primary onTouchTap={this.resetGrades} />
          </div>
        )}
        {this.state.writtenTests.length > 0 && (
          <div style={{ marginBottom: '20px' }}>
            <div>
              <span style={{ fontWeight: 'bold' }}>Written Tests:</span>
            </div>
            {this.state.writtenTests.map(writtenTest => (
              <div key={writtenTest}>
                {this.state.givenGradesWritten[writtenTest] ? (
                  <div style={{ display: 'flex', alignItems: 'center', margin: '10px' }}>
                    <div style={{ color: '#000', flexBasis: '340px' }}>{formFieldText[writtenTest]}</div>{' '}
                    <div style={gradeBoxStyle[this.state.givenGradesWritten[writtenTest]]}>
                      {this.state.givenGradesWritten[writtenTest]}
                    </div>
                  </div>
                ) : (
                  <div>
                    {formFieldText[writtenTest]}
                    <RadioButtonGroup
                      name={writtenTest}
                      onChange={(e, value) => {
                        this.setGrade(true, writtenTest, value);
                      }}
                      style={{ display: 'flex', marginBottom: '40px' }}
                      valueSelected={this.state.gradesWritten[writtenTest]}
                    >
                      <RadioButton style={{ width: '160px' }} value="1" label={gradeValues['1']} />
                      <RadioButton style={{ width: '160px' }} value="0" label={gradeValues['0']} />
                      <RadioButton style={{ width: '160px' }} value="2" label={gradeValues['2']} />
                      <RadioButton style={{ width: '160px' }} value="3" label={gradeValues['3']} />
                    </RadioButtonGroup>
                  </div>
                )}
              </div>
            ))}
          </div>
        )}
        {this.state.practicalExams.length > 0 && (
          <div>
            <div>
              <span style={{ fontWeight: 'bold' }}>Practical Exams:</span>
            </div>
            {this.state.practicalExams.map(practicalExam => (
              <div key={practicalExam}>
                {this.state.givenGradesPractical[practicalExam] ? (
                  <div style={{ display: 'flex', alignItems: 'center', margin: '10px' }}>
                    <div style={{ color: '#000', flexBasis: '340px' }}>{formFieldText[practicalExam]}</div>{' '}
                    <div style={gradeBoxStyle[this.state.givenGradesPractical[practicalExam]]}>
                      {this.state.givenGradesPractical[practicalExam]}
                    </div>
                  </div>
                ) : (
                  <div>
                    {formFieldText[practicalExam]}
                    <RadioButtonGroup
                      name={practicalExam}
                      onChange={(e, value) => {
                        this.setGrade(false, practicalExam, value);
                      }}
                      style={{ display: 'flex', marginBottom: '40px' }}
                      valueSelected={this.state.gradesPractical[practicalExam]}
                    >
                      <RadioButton style={{ width: '160px' }} value="1" label={gradeValues['1']} />
                      <RadioButton style={{ width: '160px' }} value="0" label={gradeValues['0']} />
                      <RadioButton style={{ width: '160px' }} value="2" label={gradeValues['2']} />
                      <RadioButton style={{ width: '160px' }} value="3" label={gradeValues['3']} />
                    </RadioButtonGroup>
                  </div>
                )}
              </div>
            ))}
          </div>
        )}
      </MUIDialog>
    );
  }
}
