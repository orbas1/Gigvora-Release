import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../state/ads_blocs.dart';

class AffiliateDashboardPage extends StatefulWidget {
  const AffiliateDashboardPage({super.key});

  static Widget builder(BuildContext context) => const AffiliateDashboardPage();

  @override
  State<AffiliateDashboardPage> createState() => _AffiliateDashboardPageState();
}

class _AffiliateDashboardPageState extends State<AffiliateDashboardPage> {
  final TextEditingController _email = TextEditingController();

  @override
  void initState() {
    super.initState();
    context.read<AffiliateBloc>().refresh();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Affiliate Referrals & Payouts')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _email,
                    decoration: const InputDecoration(labelText: 'Referral email'),
                  ),
                ),
                const SizedBox(width: 12),
                ElevatedButton(
                  onPressed: () async {
                    final referral = AffiliateReferral(
                      id: 0,
                      referrerId: 0,
                      referredEmail: _email.text,
                      status: 'pending',
                      commission: 0,
                    );
                    await context.read<AffiliateBloc>().createReferral(referral);
                  },
                  child: const Text('Send Invite'),
                )
              ],
            ),
            const SizedBox(height: 16),
            Expanded(
              child: BlocBuilder<AffiliateBloc, AffiliateState>(
                builder: (context, state) {
                  if (state.status == AffiliateStatus.loading) {
                    return const Center(child: CircularProgressIndicator());
                  }
                  if (state.status == AffiliateStatus.error) {
                    return Center(child: Text(state.error ?? 'Error'));
                  }
                  return ListView(
                    children: [
                      const Text('Referrals', style: TextStyle(fontWeight: FontWeight.bold)),
                      ...state.referrals.map(
                        (r) => ListTile(
                          title: Text(r.referredEmail),
                          subtitle: Text(r.status),
                          trailing: Text('Commission: \\$${r.commission.toStringAsFixed(2)}'),
                        ),
                      ),
                      const Divider(),
                      const Text('Payouts', style: TextStyle(fontWeight: FontWeight.bold)),
                      ...state.payouts.map(
                        (p) => ListTile(
                          title: Text('Payout #${p.id}'),
                          subtitle: Text('${p.status} on ${p.payoutDate.toLocal()}'),
                          trailing: Text('Amount: \\$${p.amount.toStringAsFixed(2)}'),
                        ),
                      ),
                    ],
                  );
                },
              ),
            )
          ],
        ),
      ),
    );
  }
}
