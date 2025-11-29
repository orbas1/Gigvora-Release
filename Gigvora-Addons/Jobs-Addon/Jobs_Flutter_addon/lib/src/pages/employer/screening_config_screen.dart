import 'package:flutter/material.dart';

class ScreeningConfigScreen extends StatefulWidget {
  const ScreeningConfigScreen({super.key});

  @override
  State<ScreeningConfigScreen> createState() => _ScreeningConfigScreenState();
}

class _ScreeningConfigScreenState extends State<ScreeningConfigScreen> {
  final List<_Question> _questions = [];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Screening Templates')),
      floatingActionButton: FloatingActionButton(
        onPressed: _addQuestion,
        child: const Icon(Icons.add),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: _questions
            .asMap()
            .entries
            .map(
              (entry) => Card(
                child: ListTile(
                  title: TextField(
                    decoration: const InputDecoration(labelText: 'Question'),
                    onChanged: (v) => entry.value.prompt = v,
                  ),
                  subtitle: DropdownButtonFormField<String>(
                    value: entry.value.type,
                    items: const [
                      DropdownMenuItem(value: 'short', child: Text('Short Answer')),
                      DropdownMenuItem(value: 'yes_no', child: Text('Yes / No')),
                      DropdownMenuItem(value: 'multiple', child: Text('Multiple Choice')),
                    ],
                    onChanged: (v) => setState(() => entry.value.type = v ?? 'short'),
                  ),
                  trailing: Switch(
                    value: entry.value.knockout,
                    onChanged: (v) => setState(() => entry.value.knockout = v),
                  ),
                ),
              ),
            )
            .toList(),
      ),
    );
  }

  void _addQuestion() {
    setState(() => _questions.add(_Question()));
  }
}

class _Question {
  String prompt = '';
  String type = 'short';
  bool knockout = false;
}
