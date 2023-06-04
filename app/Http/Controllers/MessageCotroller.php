<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User ;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class MessageCotroller extends Controller
{
    use HttpResponses ;
    public function createConversation(Request $request , $idReceiver)
    {
        $messageText = $request->input('message_text');
        $receiver = User::find($idReceiver);
        if($receiver) :
            Message::create([
                'idSender' => Auth::user()->id,
                'idReceiver' => $idReceiver,
                'message_text' => $messageText,
            ]);

            return $this->success([] , 'message created successfully');

        endif;    

        return $this->error([] , 'user not found' , 403);
    }
    

    public function getConversations($userId)
    {
        $user = User::find($userId);
    
        $messages = collect();
    
        // Fetch the received messages for the user
        $receivedMessages = $user->receivedMessages;
    
        // Get the distinct IDs of the senders from received messages
        $senderIds = $receivedMessages->pluck('idSender')->unique();
    
        // Get the distinct IDs of the receivers from sent messages
        $receiverIds = $user->sentMessages->pluck('idReceiver')->unique();
    
        // Combine the sender IDs and receiver IDs
        $allIds = $senderIds->concat($receiverIds)->unique();
    
        // Loop through all IDs and retrieve the corresponding users
        foreach ($allIds as $id) {
            // Retrieve the user by ID
            $otherUser = User::where('id' , $id)->with('extra_user')->first();
    
            // Filter the received messages for the current user
            $userReceivedMessages = $receivedMessages->where('idSender', $id);
    
            // Filter the sent messages for the current user
            $userSentMessages = $user->sentMessages->where('idReceiver', $id);
            // Combine the received and sent messages
            $conversationMessages = $userReceivedMessages->concat($userSentMessages)
                ->sortBy('created_at')->values();
    
            // Create the conversation array
            $conversation = [
                'idReceiver' => $id,
                'receiver_name' => $otherUser->name,
                'receiver_pseudo' => $otherUser->pseudo,
                'receiver_pp' => $otherUser->extra_user->pp ?? null ,
                'messages' => $conversationMessages->map(function ($message) use ($user, $otherUser) {
                    $senderName = ($message->idSender == $user->id) ? $user->name : $otherUser->name;
                    return [
                        'idMessage' => $message->idMessage,
                        'idSender' => $message->idSender,
                        'sender_name' => $senderName,
                        'idReceiver' => $message->idReceiver,
                        'message_text' => $message->message_text,
                        'created_at' => $message->created_at,
                        'updated_at' => $message->updated_at,
                    ];
                }),
            ];
    
            $messages->push($conversation);
        }
    
        return response()->json($messages);
    }
    
    

    // public function deleteMessage ($id)
    // {
    //     $message = Mesasge::find($id);

    //     if (!$message) {
    //         return response()->json(['message' => 'Message not found'], 404);
    //     }

    //     $message->delete();

    //     return response()->json(['message' => 'Message deleted successfully']);
    // }


    public function deleteConversation(Request $request, $userId)
    {
        Message::where('idSender', $userId)->orWhere('idReceiver', $userId)->delete();

        return response()->json(['message' => 'Conversation deleted successfully']);
    }
}

