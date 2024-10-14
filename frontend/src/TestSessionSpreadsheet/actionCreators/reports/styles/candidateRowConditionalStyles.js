export const getPaymentStatusConditionalStyle = (value, row, column) => {
  switch (value) {
    case 'Invoice': {
      return {
        range: `${column}${row}`,
        style: {
          font: {
            color: {
              argb: 'FF000000'
            }
          },
          fill: {
            fillType: 'solid',
            startColor: {
              argb: 'FF4379CD'
            }
          }
        }
      };
    }
    case 'Invoice - Collect Payment': {
      return {
        range: `${column}${row}`,
        style: {
          font: {
            color: {
              argb: 'FFFFFFFF'
            }
          },
          fill: {
            fillType: 'solid',
            startColor: {
              argb: 'FFFF0000'
            }
          }
        }
      };
    }
    case 'Invoiced': {
      return {
        range: `${column}${row}`,
        style: {
          font: {
            color: {
              argb: 'FF000000'
            }
          },
          fill: {
            fillType: 'solid',
            startColor: {
              argb: 'FFFD910D'
            }
          }
        }
      };
    }
    case 'Payment Due': {
      return {
        range: `${column}${row}`,
        style: {
          font: {
            color: {
              argb: 'FFFFFFFF'
            }
          },
          fill: {
            fillType: 'solid',
            startColor: {
              argb: 'FFFF0000'
            }
          }
        }
      };
    }
    case 'Paid in Full': {
      return {
        range: `${column}${row}`,
        style: {
          font: {
            color: {
              argb: 'FF000000'
            }
          },
          fill: {
            fillType: 'solid',
            startColor: {
              argb: 'FF81CA3E'
            }
          }
        }
      };
    }
    default:
  }
};

const getGradeConditionalStyle = (value, row, column) => {
  switch (value) {
    case 'SD':
    case 'Fail': {
      return {
        range: `${column}${row}`,
        style: {
          font: {
            color: {
              argb: 'FF000000'
            }
          },
          fill: {
            fillType: 'solid',
            startColor: {
              argb: 'FFFF0000'
            }
          }
        }
      };
    }
    case 'Pass': {
      return {
        range: `${column}${row}`,
        style: {
          font: {
            color: {
              argb: 'FF000000'
            }
          },
          fill: {
            fillType: 'solid',
            startColor: {
              argb: 'FF00FF00'
            }
          }
        }
      };
    }
    case 'Did Not Test': {
      return {
        range: `${column}${row}`,
        style: {
          font: {
            color: {
              argb: 'FF000000'
            }
          },
          fill: {
            fillType: 'solid',
            startColor: {
              argb: 'FFFFFF00'
            }
          }
        }
      };
    }
    default:
  }
};

const gradeColumnMappings = {
  W: 22,
  X: 23,
  Y: 24,
  Z: 25,
  AA: 26
};

export const getCandidateRowConditionalStyles = (candidateRows, offset) => candidateRows.reduce((acc, candidateRow, index) => {
    const row = index + offset;
    const newAcc = [];

    if (index % 2 !== 0) {
      newAcc.push({
        range: `A${row}:AA${row}`,
        style: {
          fill: {
            fillType: 'solid',
            startColor: {
              argb: 'FFDBDCDE'
            }
          }
        }
      });
    }

    const paymentStatusStyle = getPaymentStatusConditionalStyle(candidateRow[19], row, 'T');

    if (paymentStatusStyle) {
      newAcc.push(paymentStatusStyle);
    }

    Object.keys(gradeColumnMappings).forEach(key => {
      const result = getGradeConditionalStyle(candidateRow[gradeColumnMappings[key]], row, key);
      if (result) {
        newAcc.push(result);
      }
    });

    return [...acc, ...newAcc];
  }, []);

export const getScheduleRowConditionalStyle = dayNum => {
  switch (dayNum) {
    case 1: {
      return {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'B3A2C7'
          }
        }
      };
    }
    case 2: {
      return {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: '93CDDD'
          }
        }
      };
    }
    case 3: {
      return {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'C3D69B'
          }
        }
      };
    }
    case 4: {
      return {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'DFF0D8'
          }
        }
      };
    }
    case 5: {
      return {
        fill: {
          fillType: 'solid',
          startColor: {
            argb: 'F6F6F6'
          }
        }
      };
    }
    default:
  }
};
