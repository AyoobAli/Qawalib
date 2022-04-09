    <table>
        <tr>
            <td><!--{$lang_UserID}--></td>
            <td><!--{$lang_UserName}--></td>
            <td><!--{$lang_UserEmail}--></td>
        </tr>
    <!--{#loop|$UserData}-->
        <tr>
            <td><!--{#item|id}--></td>
            <td><!--{#item|name|html}--></td>
            <td><!--{#item|email|html}--></td>
        </tr>
    <!--{#endloop|$UserData}-->
    </table>

    Private Variables:<br>
        - Year: <!--{$__year}--><br>
        - Month: <!--{$__month}--><br>
        - Day: <!--{$__day}--><br>
        - Date: <!--{$__date}--><br>
        - Time: <!--{$__time}--><br>
        - Timestamp: <!--{$__timestamp}--><br>
        - Template Path: <!--{$__templatePath}--><br>
        - Theme: <!--{$__theme}--><br>
        - Language: <!--{$__language}--><br>
        - Full Path: <!--{$__fullPath}--><br>