import 'package:flutter/material.dart';

class NetworkingRecapScreen extends StatelessWidget {
  const NetworkingRecapScreen({super.key, required this.contacts});

  final List<NetworkingRecapContact> contacts;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Networking Recap')),
      body: ListView.builder(
        itemCount: contacts.length,
        itemBuilder: (context, index) {
          final contact = contacts[index];
          return Card(
            margin: const EdgeInsets.all(12),
            child: Padding(
              padding: const EdgeInsets.all(12),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(contact.name, style: Theme.of(context).textTheme.titleMedium),
                  Text(contact.role ?? ''),
                  if (contact.notes != null && contact.notes!.isNotEmpty)
                    Padding(
                      padding: const EdgeInsets.symmetric(vertical: 8),
                      child: Text(contact.notes!, maxLines: 3),
                    ),
                  Wrap(
                    spacing: 8,
                    children: [
                      ActionChip(
                        label: const Text('Follow up'),
                        onPressed: () {},
                      ),
                      ActionChip(
                        label: const Text('Schedule reminder'),
                        onPressed: () {},
                      ),
                      ActionChip(
                        label: const Text('Add to Jobs'),
                        onPressed: () {},
                      ),
                      ActionChip(
                        label: const Text('Talent & AI'),
                        onPressed: () {},
                      ),
                    ],
                  )
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}

class NetworkingRecapContact {
  NetworkingRecapContact({required this.name, this.role, this.notes, this.starred = false});

  final String name;
  final String? role;
  final String? notes;
  final bool starred;
}
