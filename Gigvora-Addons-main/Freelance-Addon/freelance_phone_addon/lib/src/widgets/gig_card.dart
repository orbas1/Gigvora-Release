import 'package:flutter/material.dart';
import '../models/gig.dart';

class GigCard extends StatelessWidget {
  const GigCard({super.key, required this.gig, this.onTap});
  final Gig gig;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        title: Text(gig.title),
        subtitle: Text('Status: ${gig.status} · Queue: ${gig.ordersQueue}'),
        trailing: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [Text('\$${gig.price.toStringAsFixed(0)}'), Text('★ ${gig.rating}')],
        ),
        onTap: onTap,
      ),
    );
  }
}
