import 'package:flutter/material.dart';

class InterviewLiveScreen extends StatelessWidget {
  const InterviewLiveScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Interview Live')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Expanded(
              child: Container(
                width: double.infinity,
                decoration: BoxDecoration(color: Colors.black87, borderRadius: BorderRadius.circular(12)),
                child: const Center(
                  child: Text('Video call view', style: TextStyle(color: Colors.white)),
                ),
              ),
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                OutlinedButton(onPressed: () {}, child: const Text('Mute')),
                const SizedBox(width: 8),
                OutlinedButton(onPressed: () {}, child: const Text('Camera')),
                const Spacer(),
                ElevatedButton(onPressed: () => Navigator.pop(context), child: const Text('Leave')),
              ],
            )
          ],
        ),
      ),
    );
  }
}
