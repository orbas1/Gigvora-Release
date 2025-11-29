import 'package:flutter/material.dart';

import '../data/subscription_repository.dart';
import '../models/models.dart';

class SubscriptionScreen extends StatefulWidget {
  const SubscriptionScreen({super.key, required this.repository});

  final SubscriptionRepository repository;

  @override
  State<SubscriptionScreen> createState() => _SubscriptionScreenState();
}

class _SubscriptionScreenState extends State<SubscriptionScreen> {
  List<SubscriptionPlan> plans = <SubscriptionPlan>[];
  bool loading = true;
  String? message;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      loading = true;
      message = null;
    });
    try {
      plans = await widget.repository.fetchPlans();
    } catch (e) {
      message = e.toString();
    } finally {
      setState(() => loading = false);
    }
  }

  Future<void> _subscribe(int id) async {
    setState(() => message = 'Subscribing...');
    try {
      final status = await widget.repository.subscribe(planId: id);
      setState(() =>
          message = 'Active plan ${status.plan.name} until ${status.expiresAt}');
    } catch (e) {
      setState(() => message = e.toString());
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Jobs subscription')),
      body: loading
          ? const Center(child: CircularProgressIndicator())
          : ListView(
              padding: const EdgeInsets.all(16),
              children: [
                if (message != null)
                  Padding(
                    padding: const EdgeInsets.only(bottom: 12),
                    child: Text(
                      message!,
                      style: TextStyle(
                        color: message!.startsWith('Active')
                            ? Colors.green
                            : Colors.red,
                      ),
                    ),
                  ),
                ...plans.map(
                  (plan) => Card(
                    child: ListTile(
                      title: Text(plan.name),
                      subtitle: Text(
                          '${plan.currency} ${plan.price.toStringAsFixed(2)} • ${plan.jobLimit} jobs'),
                      trailing: FilledButton(
                        onPressed: () => _subscribe(plan.id),
                        child: const Text('Choose'),
                      ),
                    ),
                  ),
                ),
              ],
            ),
    );
  }
}
