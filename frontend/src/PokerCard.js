import React, { Component } from "react";

export default class PokerCard extends Component {
  render() {
    const { cardName } = this.props;
    return (
      <div style={{ margin: 50 }}>
        <img style={{ width: 100 }} src={`./cards/${cardName}.svg`} />
      </div>
    );
  }
}
