import 'package:flutter/material.dart';
import '../../../models/contract.dart';

class FreelancerContractsScreen extends StatelessWidget {
  const FreelancerContractsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final contracts = [
      Contract(id: 1, title: 'API build', amount: 1500, status: 'In Progress', counterpart: 'Alice', milestones: const []),
    ];
    return Scaffold(
      appBar: AppBar(title: const Text('Contracts')),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: contracts.length,
        itemBuilder: (context, index) {
          final c = contracts[index];
          return Card(
            child: ListTile(
              title: Text(c.title),
              subtitle: Text('Client: ${c.counterpart}'),
              trailing: Chip(label: Text(c.status)),
              onTap: () => Navigator.pushNamed(context, '/freelance/contract', arguments: c),
            ),
          );
        },
      ),
    );
  }
}
