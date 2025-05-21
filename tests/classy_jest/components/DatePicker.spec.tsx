import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import {describe,expect,it, jest} from '@jest/globals';
import {DatePicker} from "../../../src/resources/packages/classy/components";
import {DatePickerProps} from "../../../src/resources/packages/classy/components/DatePicker/DatePicker";

describe('DatePicker Component', () => {
  const defaultProps = {
    anchor: document.createElement('div'),
    dateWithYearFormat: 'Y-m-d',
    endDate: new Date(),
    isSelectingDate: false,
    isMultiday: false,
    onChange: jest.fn(),
    onClick: jest.fn(),
    onClose: jest.fn(),
    onFocusOutside: jest.fn(),
    showPopover: false,
    startDate: new Date(),
    startOfWeek: 0,
    currentDate: new Date(),
  } as DatePickerProps;

  it('renders correctly with default props', () => {
    const {container} = render(<DatePicker {...defaultProps} />);

    expect(container).toMatchSnapshot();
  });

  // describe('isSelectingDate variations', () => {
  //   it.each(['start', 'end', false])(
  //     'renders correctly when isSelectingDate is %s',
  //     (isSelectingDate) => {
  //       // @ts-ignore
	// 	  const {container} = render(<DatePicker {...defaultProps} isSelectingDate={isSelectingDate} />);
  //       expect(container).toMatchSnapshot()
  //     }
  //   );
  // });
  //
  // describe('isMultiday variations', () => {
  //   it.each([true, false])(
  //     'renders correctly when isMultiday is %s',
  //     (isMultiday) => {
  //       render(<DatePicker {...defaultProps} isMultiday={isMultiday} />);
  //       expect(screen.getByRole('textbox')).toBeInTheDocument();
  //     }
  //   );
  // });
  //
  // describe('showPopover variations', () => {
  //   it.each([true, false])(
  //     'renders correctly when showPopover is %s',
  //     (showPopover) => {
  //       render(<DatePicker {...defaultProps} showPopover={showPopover} />);
  //       expect(screen.getByRole('textbox')).toBeInTheDocument();
  //       if (showPopover) {
  //         expect(screen.getByText(/CalendarIcon/i)).toBeInTheDocument();
  //       } else {
  //         expect(screen.queryByText(/CalendarIcon/i)).not.toBeInTheDocument();
  //       }
  //     }
  //   );
  // });
  //
  // describe('currentDate variations', () => {
  //   it('renders correctly when currentDate is equal to startDate', () => {
  //     const currentDate = new Date(defaultProps.startDate);
  //     render(<DatePicker {...defaultProps} currentDate={currentDate} />);
  //     expect(screen.getByRole('textbox')).toBeInTheDocument();
  //   });
  //
  //   it('renders correctly when currentDate is equal to endDate', () => {
  //     const currentDate = new Date(defaultProps.endDate);
  //     render(<DatePicker {...defaultProps} currentDate={currentDate} />);
  //     expect(screen.getByRole('textbox')).toBeInTheDocument();
  //   });
  //
  //   it('renders correctly when currentDate is neither startDate nor endDate', () => {
  //     const currentDate = new Date();
  //     currentDate.setDate(currentDate.getDate() + 1); // Ensure it's different from start and end dates
  //     render(<DatePicker {...defaultProps} currentDate={currentDate} />);
  //     expect(screen.getByRole('textbox')).toBeInTheDocument();
  //   });
  // });
  //
  // describe('Function calls', () => {
  //   it('calls onChange when a date is selected', () => {
  //     const { rerender } = render(<DatePicker {...defaultProps} />);
  //     fireEvent.change(screen.getByRole('textbox'), { target: { value: '2023-10-01' } });
  //     expect(defaultProps.onChange).toHaveBeenCalledWith('start', '2023-10-01');
  //   });
  //
  //   it('calls onClick when the input is clicked', () => {
  //     render(<DatePicker {...defaultProps} />);
  //     fireEvent.click(screen.getByRole('textbox'));
  //     expect(defaultProps.onClick).toHaveBeenCalled();
  //   });
  //
  //   it('calls onClose when the popover close button is clicked', () => {
  //     const { rerender } = render(<DatePicker {...defaultProps} showPopover={true} />);
  //     fireEvent.click(screen.getByText(/Close/i)); // Assuming there's a "Close" button in CalendarPopover
  //     expect(defaultProps.onClose).toHaveBeenCalled();
  //   });
  //
  //   it('calls onFocusOutside when focus is outside the component', () => {
  //     const { rerender } = render(<DatePicker {...defaultProps} />);
  //     fireEvent.focusOut(document.body);
  //     expect(defaultProps.onFocusOutside).toHaveBeenCalled();
  //   });
  // });
});
