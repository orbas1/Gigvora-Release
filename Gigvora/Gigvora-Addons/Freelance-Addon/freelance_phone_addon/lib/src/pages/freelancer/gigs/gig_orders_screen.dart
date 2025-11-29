import 'package:flutter/material.dart';

class GigOrdersScreen extends StatelessWidget {
  const GigOrdersScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final orders = [
      {'client': 'Alice', 'gig': 'Logo design', 'status': 'In Progress', 'amount': 120},
      {'client': 'Bob', 'gig': 'App audit', 'status': 'Delivered', 'amount': 300},
    ];
    return Scaffold(
      appBar: AppBar(title: const Text('Gig Orders')),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: orders.length,
        itemBuilder: (context, index) {
          final order = orders[index];
          return Card(
            child: ListTile(
              title: Text(order['gig'] as String),
              subtitle: Text('${order['client']} • ${order['status']}'),
              trailing: Text('\$${order['amount']}'),
              onTap: () => Navigator.pushNamed(context, '/freelance/freelancer/gig-order'),
            ),
          );
        },
      ),
    );
  }
}
