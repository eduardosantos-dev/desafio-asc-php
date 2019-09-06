import React, { Component } from "react";
import "./App.css";
import Board from "./Board";

export default class App extends Component {
  constructor(props) {
    super(props);
    this.state = { matchData: {} };
  }

  loadHands() {
    fetch(`https://desafio-php-asc.appspot.com/`)
      .then(res => res.json())
      .then(res => this.setState({ matchData: res }));
  }

  componentDidMount() {
    this.loadHands();
  }

  render() {
    return <Board matchData={this.state.matchData} />;
  }
}
