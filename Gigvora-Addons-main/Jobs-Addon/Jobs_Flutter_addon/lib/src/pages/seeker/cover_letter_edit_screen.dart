import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../../models/cover_letter.dart';
import '../../state/cover_letter_state.dart';

class CoverLetterEditScreen extends StatefulWidget {
  const CoverLetterEditScreen({super.key, this.letter});

  final CoverLetter? letter;

  @override
  State<CoverLetterEditScreen> createState() => _CoverLetterEditScreenState();
}

class _CoverLetterEditScreenState extends State<CoverLetterEditScreen> {
  late TextEditingController _title;
  late TextEditingController _body;
  bool saveAsTemplate = false;

  @override
  void initState() {
    super.initState();
    _title = TextEditingController(text: widget.letter?.title ?? '');
    _body = TextEditingController(text: widget.letter?.body ?? '');
    saveAsTemplate = widget.letter?.isTemplate ?? false;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Cover Letter')),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _save(context),
        child: const Icon(Icons.save),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(controller: _title, decoration: const InputDecoration(labelText: 'Title')),
            Expanded(
              child: TextField(
                controller: _body,
                maxLines: null,
                expands: true,
                decoration: const InputDecoration(labelText: 'Body'),
              ),
            ),
            Wrap(
              spacing: 8,
              children: [
                ActionChip(label: const Text('[Company Name]'), onPressed: () => _insert('[Company Name]')),
                ActionChip(label: const Text('[Job Title]'), onPressed: () => _insert('[Job Title]')),
                ActionChip(label: const Text('[Hiring Manager]'), onPressed: () => _insert('[Hiring Manager]')),
              ],
            ),
            Row(
              children: [
                Checkbox(value: saveAsTemplate, onChanged: (value) => setState(() => saveAsTemplate = value ?? false)),
                const Text('Save as template')
              ],
            )
          ],
        ),
      ),
    );
  }

  void _insert(String tag) {
    final text = _body.text;
    final selection = _body.selection;
    final newText = text.replaceRange(selection.start, selection.end, tag);
    _body.value = TextEditingValue(
      text: newText,
      selection: TextSelection.collapsed(offset: selection.start + tag.length),
    );
  }

  void _save(BuildContext context) {
    final state = context.read<CoverLetterState>();
    state.save(
      CoverLetter(
        id: widget.letter?.id ?? 0,
        title: _title.text,
        body: _body.text,
        isTemplate: saveAsTemplate,
      ),
    );
    Navigator.pop(context);
  }
}
