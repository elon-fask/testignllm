import * as React from 'react';
import styled from 'styled-components';

const WidgetTemplate = styled.div`
  & {
    width: auto;
  }

  & .table > tbody > tr > td {
    vertical-align: middle;
  }
`;

interface WidgetProps {
  heading: React.ReactNode;
  children: React.ReactNode;
  links?: React.ReactElement<HTMLLIElement>[];
}

function Widget(props: WidgetProps) {
  return (
    <WidgetTemplate className="widget widget-item-wrapper">
      <h3>{props.heading}</h3>
      <div className="widget-item">
        <div className="widget-item-content">{props.children}</div>
      </div>
      <ul className="widget-item-links">{props.links}</ul>
    </WidgetTemplate>
  );
}

export default Widget;
