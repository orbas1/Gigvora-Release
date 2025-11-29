import 'package:flutter/material.dart';

class EmployerCompanyProfileScreen extends StatelessWidget {
  const EmployerCompanyProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Company Profile')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: ListView(
          children: const [
            TextField(decoration: InputDecoration(labelText: 'Company name')),
            TextField(decoration: InputDecoration(labelText: 'Website')),
            TextField(decoration: InputDecoration(labelText: 'Location')),
            TextField(decoration: InputDecoration(labelText: 'Industry')),
            TextField(decoration: InputDecoration(labelText: 'Size')),
            TextField(decoration: InputDecoration(labelText: 'About'), maxLines: 3),
          ],
        ),
      ),
    );
  }
}
