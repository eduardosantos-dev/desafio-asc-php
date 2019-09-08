import React, { Component } from "react";
import "./App.css";
import Table from "react-bootstrap/Table";
import Moment from "react-moment";

export default class Ranking extends Component {
  constructor(props) {
    super(props);
    this.state = { ranking: {} };
  }

  loadRanking() {
    fetch(`https://desafio-asc-php.000webhostapp.com/api/ranking.php`)
      .then(res => res.json())
      .then(res => this.setState({ ranking: res }));
  }

  componentDidMount() {
    this.loadRanking();
  }
  render() {
    const { ranking } = this.state.ranking;
    return (
      <div style={{ margin: 50 }}>
        <Table striped bordered hover responsive variant="dark">
          <thead>
            <tr>
              <th>#</th>
              <th>Nome</th>
              <th>Mão</th>
              <th>Data</th>
            </tr>
          </thead>
          <tbody>
            {ranking &&
              ranking.map((entry, index) => (
                <tr key={index}>
                  <td>{index + 1}</td>
                  <td>{entry.playerName}</td>
                  <td>{entry.handName}</td>
                  <td>
                    <Moment format="DD/MM/YYYY" date={entry.matchDate} />
                  </td>
                </tr>
              ))}
          </tbody>
        </Table>
      </div>
    );
  }
}
