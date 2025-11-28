import 'package:flutter/material.dart';
import '../../../models/contract.dart';

class ClientContractsScreen extends StatelessWidget {
  const ClientContractsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final contracts = [
      Contract(id: 1, title: 'Landing page', amount: 900, status: 'In Progress', counterpart: 'Sam', milestones: const []),
    ];
    return Scaffold(
      appBar: AppBar(title: const Text('Client Contracts')),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: contracts.length,
        itemBuilder: (context, index) {
          final c = contracts[index];
          return Card(
            child: ListTile(
              title: Text(c.title),
              subtitle: Text('Freelancer: ${c.counterpart}'),
              trailing: Chip(label: Text(c.status)),
              onTap: () => Navigator.pushNamed(context, '/freelance/contract', arguments: c),
            ),
          );
        },
      ),
    );
  }
}
